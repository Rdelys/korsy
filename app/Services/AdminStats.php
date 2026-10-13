<?php

namespace App\Services;

use App\Models\LoginEvent;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminStats
{
    /** Chiffres clés affichés en haut du dashboard. */
    public function kpis(): array
    {
        $today = today();
        $week = now()->subDays(6)->startOfDay();

        return [
            'users' => [
                'total' => User::count(),
                'clients' => User::where('role', User::ROLE_CLIENT)->count(),
                'vendeurs' => User::where('role', User::ROLE_VENDEUR)->count(),
                'today' => User::where('created_at', '>=', $today)->count(),
                'pending_profile' => User::whereNull('profile_verified_at')->count(),
            ],
            'logins' => [
                'total' => LoginEvent::count(),
                'today' => LoginEvent::where('created_at', '>=', $today)->count(),
                'week' => LoginEvent::where('created_at', '>=', $week)->count(),
            ],
            'visits' => [
                'total' => Visit::count(),
                'today' => Visit::where('created_at', '>=', $today)->count(),
                'week' => Visit::where('created_at', '>=', $week)->count(),
            ],
        ];
    }

    /**
     * Séries pour le diagramme : 30 jours, 12 semaines, 12 mois
     * (visites, connexions, inscriptions).
     */
    public function chart(): array
    {
        $now = now();
        $from = $now->copy()->startOfMonth()->subMonths(11);

        $daily = [
            'visits' => $this->daily('visits', $from),
            'logins' => $this->daily('login_events', $from),
            'signups' => $this->daily('users', $from),
        ];

        $out = [];
        foreach (['day', 'week', 'month'] as $g) {
            $out[$g] = ['labels' => [], 'visits' => [], 'logins' => [], 'signups' => []];
        }

        // 30 derniers jours
        for ($i = 29; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i)->startOfDay();
            $out['day']['labels'][] = $d->translatedFormat('d M');
            foreach ($daily as $key => $rows) {
                $out['day'][$key][] = $rows[$d->toDateString()] ?? 0;
            }
        }

        // 12 dernières semaines (lundi → dimanche)
        for ($i = 11; $i >= 0; $i--) {
            $start = $now->copy()->startOfWeek()->subWeeks($i);
            $end = $start->copy()->endOfWeek();
            $out['week']['labels'][] = 'S'.$start->isoWeek().' · '.$start->translatedFormat('d M');
            foreach ($daily as $key => $rows) {
                $out['week'][$key][] = $this->sum($rows, $start, $end);
            }
        }

        // 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $start = $now->copy()->startOfMonth()->subMonths($i);
            $end = $start->copy()->endOfMonth();
            $out['month']['labels'][] = ucfirst($start->translatedFormat('M Y'));
            foreach ($daily as $key => $rows) {
                $out['month'][$key][] = $this->sum($rows, $start, $end);
            }
        }

        return $out;
    }

    /** Sources des visites : aujourd'hui, 7 derniers jours, 30 derniers jours. */
    public function sources(): array
    {
        $ranges = [
            'day' => today(),
            'week' => now()->subDays(6)->startOfDay(),
            'month' => now()->subDays(29)->startOfDay(),
        ];

        $out = [];
        foreach ($ranges as $key => $from) {
            $total = Visit::where('created_at', '>=', $from)->count();

            $rows = DB::table('visits')
                ->where('created_at', '>=', $from)
                ->selectRaw('source, COUNT(*) as c')
                ->groupBy('source')
                ->orderByDesc('c')
                ->limit(8)
                ->get();

            $out[$key] = [
                'total' => $total,
                'rows' => $rows->map(fn ($r) => [
                    'source' => $r->source,
                    'count' => (int) $r->c,
                    'pct' => $total ? (int) round($r->c / $total * 100) : 0,
                ])->all(),
            ];
        }

        return $out;
    }

    /** @return array<string,int> [ 'YYYY-MM-DD' => nombre ] */
    private function daily(string $table, Carbon $from): array
    {
        return DB::table($table)
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupByRaw('DATE(created_at)')
            ->pluck('c', 'd')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    private function sum(array $daily, Carbon $start, Carbon $end): int
    {
        $total = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $total += $daily[$d->toDateString()] ?? 0;
        }

        return $total;
    }
}