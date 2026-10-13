@extends('layouts.app')

@section('title', 'Mon profil — '.($user->isVendeur() ? 'Espace Vendeur' : 'Espace Apprenant').' | Tafely Kôrsy')
@section('meta_robots', 'noindex, nofollow')

@push('styles')
    <style>
        .scrollbar-none { scrollbar-width: none; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
    </style>
@endpush

@section('content')
@php
    /*
     * ------------------------------------------------------------------
     *  Données de la page. RÉEL = lu en base ; STATIQUE = valeur de démonstration
     *  (à brancher quand les modules correspondants existeront).
     * ------------------------------------------------------------------
     */
    $isVendeur = $user->isVendeur();
    $money     = fn ($n) => number_format($n, 0, ',', "\u{00A0}");

    // Identité (RÉEL) : intitulé, ville, établissement et série/matière lus en base.
    $headline   = $user->headline ?: ($isVendeur ? 'Enseignant sur Tafely Kôrsy' : 'Apprenant sur Tafely Kôrsy');
    $city       = $user->city ?: 'Madagascar';
    $shortLevel = $user->level ? \Illuminate\Support\Str::before($user->level, ' (') : null;
    $schoolLine = $user->school
        ? $user->school.(! $isVendeur && $shortLevel ? ' ('.$shortLevel.')' : '')
        : (! $isVendeur ? $shortLevel : null);

    // Téléphone (RÉEL) : stocké en +261XXXXXXXXX, affiché « 34 12 345 67 ».
    $phoneLocal = preg_replace('/^\+?261/', '', (string) $user->phone);
    $phoneFmt   = preg_replace('/^(\d{2})(\d{2})(\d{3})(\d{2})$/', '$1 $2 $3 $4', $phoneLocal);
    $phoneField = old('phone') ? preg_replace('/^\+?261/', '', old('phone')) : $phoneFmt;

    // Opérateurs (config/mobile_money.php) ; l'actif est détecté d'après le préfixe du numéro (RÉEL).
    $operators = config('mobile_money.operators');
    $activeOp  = collect($operators)->search(
        fn ($o) => in_array(substr($phoneLocal, 0, 2), $o['prefixes'], true)
    ) ?: null;

    // Onglets : libellés selon le profil.
    $tabs = $isVendeur
        ? ['info' => ['person', 'Informations Professionnelles'], 'pay' => ['contactless', 'Reversements & Devise'], 'history' => ['storefront', 'Publications & Ventes'], 'creds' => ['workspace_premium', 'Certification & Badges'], 'security' => ['shield', 'Sécurité & Préférences']]
        : ['info' => ['person', 'Informations Personnelles & Études'], 'pay' => ['contactless', 'Paiement Mobile Money & Devise'], 'history' => ['receipt_long', 'Historique des Achats & Factures'], 'creds' => ['military_tech', 'Attestations & Badges'], 'security' => ['shield', 'Sécurité & Préférences']];

    // Onglet ouvert au chargement : retour serveur (succès / erreurs) sinon « info » ; le #hash de l'URL est géré en JS.
    $serverTab = session('profile_tab')
        ?? ($errors->hasBag('password') && $errors->getBag('password')->any() ? 'security' : ($errors->any() ? 'info' : null));
    $startTab = $serverTab ?? 'info';

    // Indicateurs clés (STATIQUES).
    $metrics = $isVendeur
        ? [
            ['label' => 'Documents publiés', 'icon' => 'folder_open', 'tone' => 'bg-surface-container text-primary-container', 'value' => '12', 'unit' => 'documents', 'sub' => '8 Fiches PDF, 4 Masterclasses'],
            ['label' => 'Gains disponibles', 'icon' => 'payments', 'tone' => 'bg-secondary-fixed/40 text-secondary', 'value' => $money(84500), 'unit' => 'Ar', 'vc' => 'text-secondary', 'uc' => 'text-secondary font-bold', 'ring' => true, 'cta' => ['Demander un retrait', 'pay']],
            ['label' => 'Ventes ce mois', 'icon' => 'shopping_bag', 'tone' => 'bg-[#10b981]/10 text-[#047857]', 'value' => '37', 'unit' => 'ventes', 'sub' => '+12 % par rapport au mois dernier'],
            ['label' => 'Note moyenne', 'icon' => 'star', 'tone' => 'bg-surface-container text-primary-container', 'value' => '4,8', 'unit' => '/ 5', 'progress' => 96, 'sub' => '126 avis d\'apprenants'],
        ]
        : [
            ['label' => 'Documents & Cours acquis', 'icon' => 'folder_open', 'tone' => 'bg-surface-container text-primary-container', 'value' => '5', 'unit' => 'ressources', 'sub' => '3 Fiches PDF, 2 Masterclasses'],
            ['label' => 'Solde disponible', 'icon' => 'payments', 'tone' => 'bg-secondary-fixed/40 text-secondary', 'value' => $money(12500), 'unit' => 'Ar', 'vc' => 'text-secondary', 'uc' => 'text-secondary font-bold', 'ring' => true, 'cta' => ['Recharger via Mobile Money', 'pay']],
            ['label' => 'Attestations officielles', 'icon' => 'verified_user', 'tone' => 'bg-[#10b981]/10 text-[#047857]', 'value' => '1', 'unit' => 'Validée QR', 'uc' => 'text-[#047857] font-semibold', 'sub' => 'Module Analyse Mathématique Sup'],
            ['label' => 'Temps de révision', 'icon' => 'timelapse', 'tone' => 'bg-surface-container text-primary-container', 'value' => '18h', 'unit' => 'ce mois', 'progress' => 72],
        ];

    // Classes partagées avec les onglets (les @include héritent de ces variables).
    $card     = 'bg-surface-container-lowest rounded-xl border border-outline-variant/30 p-space-lg sm:p-space-xl shadow-sm';
    $cardHead = 'flex items-center justify-between gap-2 border-b border-outline-variant/20 pb-4 mb-space-lg';
    $label    = 'block text-label-md font-label-md text-primary font-bold';
    $ico      = 'material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]';
    $input    = 'w-full pl-9 pr-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-[16px] sm:text-body-md font-body-md text-primary focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container';
    $errText  = 'mt-1 text-label-sm font-label-sm text-error';
@endphp

<div class="w-full max-w-7xl mx-auto px-space-md sm:px-space-xl py-space-lg flex flex-col gap-space-lg">

    {{-- ============ 2. FIL D'ARIANE ============ --}}
    <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-label-md font-label-md text-on-surface-variant">
        <a href="{{ url('/') }}" class="hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]" aria-hidden="true">home</span><span>Accueil</span>
        </a>
        <span class="text-outline-variant text-[14px]" aria-hidden="true">/</span>
        <span>{{ $isVendeur ? 'Espace Vendeur' : 'Espace Apprenant' }}</span>
        <span class="text-outline-variant text-[14px]" aria-hidden="true">/</span>
        <span aria-current="page" class="text-primary font-bold">Mon Profil</span>
    </nav>

    {{-- ============ 3. HERO / CARTE D'IDENTITÉ ============ --}}
    <section class="{{ $card }} relative overflow-hidden">
        <div @class([
            'absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r',
            'from-primary-container via-secondary to-primary-container' => ! $isVendeur,
            'from-secondary via-primary-container to-secondary' => $isVendeur,
        ]) aria-hidden="true"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-lg pt-space-xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-space-lg min-w-0">

                {{-- Avatar (pas de photo en base : initiale) --}}
                <div class="relative shrink-0">
                    <span @class([
                        'w-24 h-24 sm:w-28 sm:h-28 rounded-full ring-4 ring-surface-container-low shadow-sm flex items-center justify-center text-4xl font-headline-md font-bold text-on-primary',
                        'bg-primary-container' => ! $isVendeur,
                        'bg-secondary' => $isVendeur,
                    ])>{{ $user->initial() }}</span>
                    <div class="absolute -bottom-1 -right-1 bg-surface-container-lowest p-1 rounded-full shadow-sm"
                         title="{{ $user->email_verified_at ? 'E-mail vérifié' : 'E-mail non vérifié' }}">
                        <span class="material-symbols-outlined icon-fill text-[22px] {{ $user->email_verified_at ? 'text-on-tertiary-container' : 'text-outline' }}" aria-hidden="true">{{ $user->email_verified_at ? 'verified' : 'pending' }}</span>
                    </div>
                </div>

                <div class="space-y-1.5 min-w-0">
                    <div class="flex flex-wrap items-center gap-space-sm">
                        <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary tracking-tight break-words">{{ $user->name }}</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-label-sm font-label-sm bg-surface-container text-primary-container border border-outline-variant/40">
                            <span class="material-symbols-outlined icon-fill text-[13px]" aria-hidden="true">{{ $isVendeur ? 'co_present' : 'school' }}</span>
                            {{ $isVendeur ? 'Enseignant' : 'Matricule' }} #{{ $user->matricule() }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-[#10b981]/10 text-[#047857]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span>Compte Actif
                        </span>
                        <span @class([
                            'inline-flex items-center gap-1 px-2 py-0.5 rounded text-label-sm font-label-sm font-bold',
                            'text-primary bg-primary-fixed' => ! $isVendeur,
                            'text-on-secondary bg-secondary' => $isVendeur,
                        ])>
                            @if ($isVendeur)<span class="material-symbols-outlined icon-fill text-xs" aria-hidden="true">verified</span>@endif
                            {{ $user->roleLabel() }}
                        </span>
                    </div>

                    {{-- Intitulé (RÉEL) --}}
                    <p class="text-body-md font-body-md text-primary font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[18px]" aria-hidden="true">workspace_premium</span>
                        <span>{{ $headline }}</span>
                    </p>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-body-sm font-body-sm text-on-surface-variant">
                        {{-- Ville (RÉEL) --}}
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-outline" aria-hidden="true">location_on</span>{{ $city }}</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-outline" aria-hidden="true">calendar_today</span>{{ $isVendeur ? 'Vendeur' : 'Inscrit' }} depuis {{ ucfirst($user->created_at->translatedFormat('F Y')) }}</span>
                        {{-- Établissement / série (RÉEL) --}}
                        @if ($schoolLine)
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-outline" aria-hidden="true">auto_stories</span>{{ $schoolLine }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions rapides (STATIQUES) --}}
            <div class="flex sm:flex-col gap-2 shrink-0 justify-end">
                <button type="button" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-outline-variant hover:bg-surface-container-low text-primary text-label-md font-label-md transition-colors active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[18px]" aria-hidden="true">photo_camera</span><span>Modifier la photo</span>
                </button>
                <button type="button" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-outline-variant/60 hover:bg-surface-container-low text-on-surface-variant text-label-md font-label-md transition-colors active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[18px]" aria-hidden="true">download</span><span>Exporter mes données</span>
                </button>
            </div>
        </div>
    </section>

    {{-- ============ 4. INDICATEURS CLÉS ============ --}}
    <section aria-label="Indicateurs clés du compte" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
        @foreach ($metrics as $m)
            <div @class([
                'bg-surface-container-lowest p-space-lg rounded-xl border border-outline-variant/30 shadow-sm hover:border-outline-variant transition-colors flex flex-col justify-between',
                'ring-1 ring-secondary/20' => ! empty($m['ring']),
            ])>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-label-md font-label-md text-on-surface-variant">{{ $m['label'] }}</span>
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $m['tone'] }}">
                        <span class="material-symbols-outlined icon-fill text-[20px]" aria-hidden="true">{{ $m['icon'] }}</span>
                    </div>
                </div>
                <div class="mt-space-md">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-price-display font-bold {{ $m['vc'] ?? 'text-primary' }}">{{ $m['value'] }}</span>
                        <span class="text-label-sm font-label-sm {{ $m['uc'] ?? 'text-outline' }}">{{ $m['unit'] }}</span>
                    </div>
                    @isset($m['sub'])
                        <p class="text-body-sm font-body-sm text-on-surface-variant mt-1">{{ $m['sub'] }}</p>
                    @endisset
                    @isset($m['cta'])
                        <a href="#pay" data-goto-tab="pay" class="text-body-sm font-body-sm text-primary hover:text-secondary font-bold mt-1 inline-flex items-center gap-1 group">
                            <span>{{ $m['cta'][0] }}</span>
                            <span class="material-symbols-outlined text-[14px] group-hover:translate-x-0.5 transition-transform" aria-hidden="true">arrow_forward</span>
                        </a>
                    @endisset
                    @isset($m['progress'])
                        <div class="w-full bg-surface-container-high h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-primary-container h-full rounded-full" style="width: {{ $m['progress'] }}%;"></div>
                        </div>
                    @endisset
                </div>
            </div>
        @endforeach
    </section>

    {{-- ============ 5. ONGLETS DE GESTION DU PROFIL ============ --}}
    <div id="profile-tabs" role="tablist" aria-label="Gestion du profil"
         class="border-b border-outline-variant/40 flex items-center gap-space-lg overflow-x-auto scrollbar-none">
        @foreach ($tabs as $key => [$icon, $text])
            <button type="button" role="tab" data-profile-tab="{{ $key }}" aria-controls="profile-panel-{{ $key }}" aria-selected="{{ $key === $startTab ? 'true' : 'false' }}"
                    @class([
                        'pb-3 px-1 text-label-lg font-label-lg flex items-center gap-2 whitespace-nowrap border-b-2 transition-colors',
                        'text-primary font-bold border-secondary' => $key === $startTab,
                        'text-on-surface-variant hover:text-on-surface border-transparent' => $key !== $startTab,
                    ])>
                <span class="material-symbols-outlined text-[18px]" aria-hidden="true">{{ $icon }}</span>
                <span>{{ $text }}</span>
            </button>
        @endforeach
    </div>

    {{-- ============ 6. CONTENU PRINCIPAL + COLONNE LATÉRALE ============ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">
        <div class="lg:col-span-8 min-w-0">
            @foreach (array_keys($tabs) as $key)
                <div id="profile-panel-{{ $key }}" role="tabpanel" data-profile-panel="{{ $key }}"
                     @class(['space-y-space-xl', 'hidden' => $key !== $startTab])>
                    @include('profile.tabs.'.$key)
                </div>
            @endforeach
        </div>

        <aside class="lg:col-span-4 space-y-space-lg">
            @include('profile.tabs._sidebar')
        </aside>
    </div>

    {{-- ============ 8. BARRE D'ACTIONS ============ --}}
    <div class="{{ $card }} !p-space-md sm:!p-space-lg flex flex-col sm:flex-row items-center justify-between gap-space-md">
        <div class="flex items-center gap-2 text-body-sm font-body-sm text-outline">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">history</span>
            <span>Dernière modification enregistrée le {{ $user->updated_at->translatedFormat('d F Y \à H:i') }}</span>
        </div>
        <div class="flex items-center gap-space-md w-full sm:w-auto justify-end">
            <button type="reset" form="profile-form" class="px-5 py-2.5 rounded-lg border border-outline-variant hover:bg-surface-container-low text-primary text-label-lg font-label-lg transition-colors">Annuler</button>
            <button type="submit" form="profile-form" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-on-primary text-label-lg font-label-lg font-bold transition-all shadow-sm active:scale-[0.98] flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]" aria-hidden="true">save</span><span>Enregistrer les modifications</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var KEYS = @js(array_keys($tabs));
        var tabs = document.querySelectorAll('[data-profile-tab]');
        var panels = document.querySelectorAll('[data-profile-panel]');

        function show(key, updateHash) {
            tabs.forEach(function (t) {
                var on = t.getAttribute('data-profile-tab') === key;
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                ['text-primary', 'font-bold', 'border-secondary'].forEach(function (c) { t.classList.toggle(c, on); });
                ['text-on-surface-variant', 'border-transparent'].forEach(function (c) { t.classList.toggle(c, !on); });
            });
            panels.forEach(function (p) { p.classList.toggle('hidden', p.getAttribute('data-profile-panel') !== key); });
            if (updateHash && window.history.replaceState) { window.history.replaceState(null, '', '#' + key); }
        }

        tabs.forEach(function (t) {
            t.addEventListener('click', function () { show(t.getAttribute('data-profile-tab'), true); });
        });

        // Liens internes (« Recharger », « Modifier »…) qui ouvrent un onglet,
        // avec un focus optionnel sur un champ (data-focus="id-du-champ").
        document.querySelectorAll('[data-goto-tab]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                show(a.getAttribute('data-goto-tab'), true);
                document.getElementById('profile-tabs').scrollIntoView({ behavior: 'smooth', block: 'start' });

                var focusId = a.getAttribute('data-focus');
                if (focusId) {
                    var el = document.getElementById(focusId);
                    if (el) {
                        setTimeout(function () {
                            el.focus();
                            if (el.select) { el.select(); }
                        }, 350);
                    }
                }
            });
        });

        // Un retour serveur (succès / erreurs) prime sur le #hash ; sinon on respecte le #hash de l'URL.
        @if ($serverTab === null)
            var hash = window.location.hash.replace('#', '');
            if (KEYS.indexOf(hash) !== -1) { show(hash, false); }
        @endif
    })();
</script>
@endpush