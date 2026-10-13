@extends('admin.layouts.app')

@section('title', 'Sécurité')
@section('heading', 'Sécurité')

@section('content')
@php
    $card = 'bg-white rounded-xl border border-outline-variant/50 shadow-sm';
    $tabs = ['suspicious' => 'Tentatives suspectes', 'success' => 'Connexions réussies', 'all' => 'Tout'];
@endphp

{{-- Alerte : tentatives avec un e-mail non autorisé ces dernières 24 h --}}
@if ($stats['unauthorized_24h'] > 0)
    <div role="alert" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
        <span class="material-symbols-outlined icon-fill shrink-0 mt-0.5" aria-hidden="true">gpp_maybe</span>
        <div class="text-sm">
            <p class="font-bold">
                {{ $stats['unauthorized_24h'] }} tentative(s) de connexion à l'administration avec un e-mail non autorisé ces dernières 24 h.
            </p>
            <p class="mt-0.5">Seule l'adresse <strong>{{ $allowedEmail }}</strong> peut se connecter. Toutes ces tentatives ont été refusées.</p>
        </div>
    </div>
@else
    <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3">
        <span class="material-symbols-outlined icon-fill shrink-0 mt-0.5" aria-hidden="true">verified_user</span>
        <p class="text-sm font-semibold">Aucune tentative avec un e-mail non autorisé ces dernières 24 h.</p>
    </div>
@endif

{{-- Indicateurs --}}
<section class="grid grid-cols-1 sm:grid-cols-3 gap-4" aria-label="Indicateurs de sécurité">
    <div class="{{ $card }} p-5">
        <p class="text-sm font-semibold text-on-surface-variant">E-mails non autorisés (24 h)</p>
        <p class="mt-2 text-3xl font-bold {{ $stats['unauthorized_24h'] ? 'text-secondary' : 'text-primary' }}">{{ $stats['unauthorized_24h'] }}</p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-sm font-semibold text-on-surface-variant">Échecs au total (24 h) · IP distinctes</p>
        <p class="mt-2 text-3xl font-bold text-primary">{{ $stats['failed_24h'] }} <span class="text-base font-semibold text-on-surface-variant">· {{ $stats['ips_24h'] }}</span></p>
    </div>
    <div class="{{ $card }} p-5">
        <p class="text-sm font-semibold text-on-surface-variant">Dernière connexion admin</p>
        @if ($stats['last_success'])
            <p class="mt-2 text-base font-bold text-primary">{{ $stats['last_success']->created_at->translatedFormat('d M Y à H:i') }}</p>
            <p class="text-xs text-on-surface-variant">IP {{ $stats['last_success']->ip }}</p>
        @else
            <p class="mt-2 text-base font-bold text-primary">—</p>
        @endif
    </div>
</section>

{{-- Journal --}}
<section class="{{ $card }} overflow-hidden">
    <div class="px-5 py-3 border-b border-outline-variant/40 flex items-center justify-between gap-3 flex-wrap">
        <h2 class="text-sm font-bold text-primary">Journal des tentatives de connexion</h2>
        <div class="inline-flex p-1 bg-surface-container-low rounded-lg">
            @foreach ($tabs as $key => $text)
                <a href="{{ route('admin.security', ['type' => $key]) }}"
                   class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ $type === $key ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-white' }}">{{ $text }}</a>
            @endforeach
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-surface-container-low text-xs uppercase tracking-wider text-on-surface-variant">
                <tr>
                    <th class="text-left font-bold px-5 py-3 whitespace-nowrap">Date</th>
                    <th class="text-left font-bold px-3 py-3">E-mail utilisé</th>
                    <th class="text-left font-bold px-3 py-3">Résultat</th>
                    <th class="text-left font-bold px-3 py-3">Adresse IP</th>
                    <th class="text-left font-bold px-5 py-3">Appareil</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                @forelse ($attempts as $a)
                    <tr class="{{ in_array($a->id, $newIds, true) ? 'bg-red-50/60' : '' }}">
                        <td class="px-5 py-3 whitespace-nowrap text-on-surface-variant">{{ $a->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-3 py-3 font-semibold text-on-surface break-all">
                            {{ $a->email }}
                            @if (in_array($a->id, $newIds, true))
                                <span class="ml-1 text-[10px] font-bold uppercase bg-secondary text-white px-1.5 py-0.5 rounded">Nouveau</span>
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $a->badgeClasses() }}">{{ $a->label() }}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-on-surface-variant">{{ $a->ip ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs text-on-surface-variant max-w-xs truncate" title="{{ $a->user_agent }}">{{ $a->user_agent ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-on-surface-variant">Aucune entrée à afficher.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($attempts->hasPages())
        <div class="px-5 py-3 border-t border-outline-variant/40">{{ $attempts->links() }}</div>
    @endif
</section>
@endsection