<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'suspicious');

        $query = AdminLoginAttempt::query()->latest('id');

        if ($type === 'success') {
            $query->where('reason', AdminLoginAttempt::SUCCESS);
        } elseif ($type !== 'all') {
            $type = 'suspicious';
            $query->where('reason', '!=', AdminLoginAttempt::SUCCESS);
        }

        $attempts = $query->paginate(20)->withQueryString();

        // Lignes encore jamais vues : surlignées, puis marquées comme vues (la pastille du menu baisse).
        $newIds = $attempts->getCollection()
            ->filter(fn ($a) => $a->seen_at === null && $a->reason !== AdminLoginAttempt::SUCCESS)
            ->pluck('id')
            ->all();

        if ($newIds) {
            AdminLoginAttempt::whereIn('id', $newIds)->update(['seen_at' => now()]);
        }

        $since = now()->subDay();
        $failed24h = AdminLoginAttempt::where('reason', '!=', AdminLoginAttempt::SUCCESS)->where('created_at', '>=', $since);

        return view('admin.security', [
            'attempts' => $attempts,
            'type' => $type,
            'newIds' => $newIds,
            'stats' => [
                'unauthorized_24h' => AdminLoginAttempt::where('reason', AdminLoginAttempt::UNAUTHORIZED_EMAIL)->where('created_at', '>=', $since)->count(),
                'failed_24h' => (clone $failed24h)->count(),
                'ips_24h' => (clone $failed24h)->distinct()->count('ip'),
                'last_success' => AdminLoginAttempt::where('reason', AdminLoginAttempt::SUCCESS)->latest('id')->first(),
            ],
            'allowedEmail' => config('admin.email'),
        ]);
    }
}