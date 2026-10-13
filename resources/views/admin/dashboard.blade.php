@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
@php
    $card = 'bg-white rounded-xl border border-outline-variant/50 shadow-sm';
    $fmt  = fn ($n) => number_format($n, 0, ',', "\u{00A0}");

    $cards = [
        ['label' => 'Utilisateurs', 'icon' => 'group', 'tone' => 'bg-primary-fixed text-primary-container',
         'value' => $kpis['users']['total'],
         'lines' => [$fmt($kpis['users']['clients']).' apprenants · '.$fmt($kpis['users']['vendeurs']).' vendeurs', '+'.$fmt($kpis['users']['today']).' aujourd\'hui']],
        ['label' => 'Connexions', 'icon' => 'login', 'tone' => 'bg-emerald-100 text-emerald-700',
         'value' => $kpis['logins']['total'],
         'lines' => [$fmt($kpis['logins']['today']).' aujourd\'hui', $fmt($kpis['logins']['week']).' sur 7 jours']],
        ['label' => 'Visites', 'icon' => 'travel_explore', 'tone' => 'bg-amber-100 text-amber-700',
         'value' => $kpis['visits']['total'],
         'lines' => [$fmt($kpis['visits']['today']).' aujourd\'hui', $fmt($kpis['visits']['week']).' sur 7 jours']],
        ['label' => 'Profils à valider', 'icon' => 'verified_user', 'tone' => 'bg-secondary-fixed text-secondary',
         'value' => $kpis['users']['pending_profile'],
         'lines' => ['En attente de validation'], 'href' => route('admin.users.index', ['status' => 'pending_profile'])],
    ];
@endphp

{{-- Chiffres clés --}}
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" aria-label="Chiffres clés">
    @foreach ($cards as $c)
        <{{ isset($c['href']) ? 'a href='.$c['href'] : 'div' }} class="{{ $card }} p-5 block {{ isset($c['href']) ? 'hover:border-primary-container transition-colors' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-on-surface-variant">{{ $c['label'] }}</span>
                <span class="w-10 h-10 rounded-lg flex items-center justify-center {{ $c['tone'] }}">
                    <span class="material-symbols-outlined icon-fill" aria-hidden="true">{{ $c['icon'] }}</span>
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold text-primary">{{ $fmt($c['value']) }}</p>
            @foreach ($c['lines'] as $line)
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $line }}</p>
            @endforeach
        </{{ isset($c['href']) ? 'a' : 'div' }}>
    @endforeach
</section>

{{-- Sélecteur de période (commun au diagramme et aux sources) --}}
<div class="flex items-center justify-between gap-3 flex-wrap">
    <h2 class="text-base font-bold text-primary">Activité</h2>
    <div class="inline-flex p-1 bg-white border border-outline-variant/60 rounded-lg" role="tablist" aria-label="Période">
        @foreach (['day' => 'Par jour', 'week' => 'Par semaine', 'month' => 'Par mois'] as $key => $text)
            <button type="button" role="tab" data-gran="{{ $key }}"
                    class="px-3.5 py-1.5 rounded-md text-sm font-semibold transition-colors">{{ $text }}</button>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

    {{-- Diagramme --}}
    <section class="{{ $card }} p-5 xl:col-span-2" aria-label="Diagramme d'activité">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-primary">Visites, connexions et inscriptions</h3>
            <span class="text-xs text-on-surface-variant" id="chart-range"></span>
        </div>
        <div class="relative h-72 sm:h-80">
            <canvas id="activity-chart"></canvas>
        </div>
    </section>

    {{-- Sources des visites --}}
    <section class="{{ $card }} p-5" aria-label="Sources des visites">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-primary">Source des visiteurs</h3>
            <span class="text-xs text-on-surface-variant" id="sources-range"></span>
        </div>

        @foreach ($sources as $gran => $block)
            <div data-sources="{{ $gran }}" class="hidden space-y-3">
                @forelse ($block['rows'] as $row)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="font-semibold text-on-surface truncate pr-2">{{ $row['source'] }}</span>
                            <span class="text-on-surface-variant shrink-0">{{ $fmt($row['count']) }} · {{ $row['pct'] }} %</span>
                        </div>
                        <div class="h-2 rounded-full bg-surface-container overflow-hidden">
                            <div class="h-full rounded-full bg-primary-container" style="width: {{ max($row['pct'], 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-on-surface-variant py-8 text-center">Aucune visite enregistrée sur cette période.</p>
                @endforelse

                @if ($block['rows'])
                    <p class="text-xs text-on-surface-variant pt-2 border-t border-outline-variant/40">Total : {{ $fmt($block['total']) }} visite(s)</p>
                @endif
            </div>
        @endforeach
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    (function () {
        var DATA = @json($chart);
        var RANGES = { day: '30 derniers jours', week: '12 dernières semaines', month: '12 derniers mois' };
        var SRC_RANGES = { day: "Aujourd'hui", week: '7 derniers jours', month: '30 derniers jours' };

        function dataset(label, key, color) {
            return { label: label, key: key, data: DATA.day[key], borderColor: color, backgroundColor: color + '22',
                     tension: 0.3, borderWidth: 2, pointRadius: 2, pointHoverRadius: 4, fill: false };
        }

        var chart = new Chart(document.getElementById('activity-chart'), {
            type: 'line',
            data: {
                labels: DATA.day.labels,
                datasets: [
                    dataset('Visites', 'visits', '#1653c9'),
                    dataset('Connexions', 'logins', '#10b981'),
                    dataset('Inscriptions', 'signups', '#c8102e')
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { ticks: { maxRotation: 0, autoSkip: true } } },
                plugins: { legend: { position: 'bottom' } }
            }
        });

        var buttons = document.querySelectorAll('[data-gran]');
        var panels = document.querySelectorAll('[data-sources]');

        function setGran(g) {
            chart.data.labels = DATA[g].labels;
            chart.data.datasets.forEach(function (ds) { ds.data = DATA[g][ds.key]; });
            chart.update();

            document.getElementById('chart-range').textContent = RANGES[g];
            document.getElementById('sources-range').textContent = SRC_RANGES[g];

            buttons.forEach(function (b) {
                var on = b.getAttribute('data-gran') === g;
                b.setAttribute('aria-selected', on ? 'true' : 'false');
                ['bg-primary', 'text-white'].forEach(function (c) { b.classList.toggle(c, on); });
                ['text-on-surface-variant', 'hover:bg-surface-container-low'].forEach(function (c) { b.classList.toggle(c, !on); });
            });
            panels.forEach(function (p) { p.classList.toggle('hidden', p.getAttribute('data-sources') !== g); });
        }

        buttons.forEach(function (b) {
            b.addEventListener('click', function () { setGran(b.getAttribute('data-gran')); });
        });

        setGran('day');
    })();
</script>
@endpush