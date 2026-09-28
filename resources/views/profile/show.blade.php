@extends('layouts.app')

@section('title', 'Mon profil | Tafely Kôrsy')
@section('meta_robots', 'noindex, nofollow')

@section('content')

    <section class="max-w-3xl mx-auto px-margin sm:px-space-xl py-10 md:py-14">

        <nav aria-label="Fil d'Ariane" class="mb-4">
            <ol class="flex items-center gap-1 text-label-sm font-label-sm text-on-surface-variant">
                <li><a href="{{ url('/') }}" class="hover:text-primary hover:underline">Accueil</a></li>
                <li aria-hidden="true"><span class="material-symbols-outlined text-xs">chevron_right</span></li>
                <li aria-current="page" class="text-primary font-bold">Mon profil</li>
            </ol>
        </nav>

        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-space-md mb-space-xl">
            <span class="w-16 h-16 rounded-full bg-primary-container text-on-primary flex items-center justify-center text-headline-md font-headline-md font-bold flex-shrink-0">
                {{ $user->initial() }}
            </span>
            <div>
                <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary tracking-tight">{{ $user->name }}</h1>
                <span @class([
                    'inline-flex items-center gap-1 mt-1 text-label-sm font-label-sm px-2 py-0.5 rounded font-bold',
                    'text-primary bg-primary-fixed' => $user->isClient(),
                    'text-on-secondary bg-secondary' => $user->isVendeur(),
                ])>
                    @if ($user->isVendeur())
                        <span class="material-symbols-outlined icon-fill text-xs" aria-hidden="true">verified</span>
                    @endif
                    {{ $user->roleLabel() }}
                </span>
            </div>
        </div>

        {{-- Coordonnées --}}
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 shadow-sm p-space-lg mb-space-lg">
            <h2 class="text-headline-sm font-headline-sm text-primary mb-space-md">Coordonnées</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div>
                    <dt class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Adresse e-mail</dt>
                    <dd class="text-body-md font-body-md text-on-surface flex items-center gap-1.5">
                        {{ $user->email }}
                        @if ($user->email_verified_at)
                            <span class="material-symbols-outlined icon-fill text-sm text-primary-container" title="E-mail vérifié" aria-hidden="true">verified</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Numéro Mobile Money</dt>
                    <dd class="text-body-md font-body-md text-on-surface">{{ $user->phone }}</dd>
                </div>
                <div>
                    <dt class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Membre depuis</dt>
                    <dd class="text-body-md font-body-md text-on-surface">{{ $user->created_at->translatedFormat('d F Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Appareils reconnus --}}
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 shadow-sm p-space-lg mb-space-lg">
            <h2 class="text-headline-sm font-headline-sm text-primary mb-1">Navigateurs reconnus</h2>
            <p class="text-body-sm font-body-sm text-on-surface-variant mb-space-md">
                Ces navigateurs n'ont plus besoin de code de vérification pour se connecter.
            </p>

            @if ($devices->isEmpty())
                <p class="text-body-sm font-body-sm text-on-surface-variant">Aucun appareil enregistré pour le moment.</p>
            @else
                <ul class="divide-y divide-outline-variant/20">
                    @foreach ($devices as $device)
                        <li class="py-space-sm flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="material-symbols-outlined text-primary-container flex-shrink-0" aria-hidden="true">devices</span>
                                <div class="min-w-0">
                                    <p class="text-label-lg font-label-lg text-on-surface truncate">{{ $device->label ?? 'Appareil inconnu' }}</p>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant">
                                        Dernière utilisation : {{ optional($device->last_used_at)->diffForHumans() ?? '—' }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Déconnexion --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg border border-outline-variant text-secondary font-label-lg text-label-lg hover:bg-secondary-fixed/40 transition-colors">
                <span class="material-symbols-outlined text-lg" aria-hidden="true">logout</span>
                <span>Se déconnecter</span>
            </button>
        </form>

    </section>

@endsection