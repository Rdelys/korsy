<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b3b8f">

    {{-- ============ SEO ============ --}}
    <title>@yield('title', 'Tafely Kôrsy')</title>
    <meta name="description" content="@yield('meta_description', 'Tafely Kôrsy : cours, documents PDF et vidéos d\'enseignants certifiés à Madagascar.')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Tafely Kôrsy">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="@yield('title', 'Tafely Kôrsy')">
    <meta property="og:description" content="@yield('meta_description', 'Tafely Kôrsy : cours, documents PDF et vidéos d\'enseignants certifiés à Madagascar.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-cover.png'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Tafely Kôrsy')">
    <meta name="twitter:description" content="@yield('meta_description', 'Tafely Kôrsy : cours, documents PDF et vidéos d\'enseignants certifiés à Madagascar.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-cover.png'))">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;0,8..60,700;1,8..60,400&display=swap" rel="stylesheet">
    {{-- Material Symbols Outlined --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (CDN — version statique. À remplacer par Vite + Tailwind lors de la mise en production) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    /* Palette : BLEU (principal / important) + ROUGE (accent / action) */
                    "colors": {
                        "primary": "#0b3b8f",
                        "primary-container": "#1653c9",
                        "primary-deep": "#061a44",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#d6e3ff",
                        "primary-fixed": "#dbe6ff",
                        "primary-fixed-dim": "#b0c6ff",
                        "on-primary-fixed": "#001b4d",
                        "on-primary-fixed-variant": "#0b3b8f",
                        "inverse-primary": "#b0c6ff",
                        "surface-tint": "#1653c9",

                        "secondary": "#c8102e",
                        "secondary-container": "#e5283f",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#ffffff",
                        "secondary-fixed": "#ffdad8",
                        "secondary-fixed-dim": "#ffb3b1",
                        "on-secondary-fixed": "#410007",
                        "on-secondary-fixed-variant": "#92001c",

                        "background": "#f6f8fe",
                        "on-background": "#0f1b33",
                        "surface": "#f6f8fe",
                        "surface-bright": "#f6f8fe",
                        "surface-dim": "#cfd8ec",
                        "surface-variant": "#d9e3f7",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#eef3fd",
                        "surface-container": "#e6edfb",
                        "surface-container-high": "#dde6f8",
                        "surface-container-highest": "#d3def5",
                        "on-surface": "#0f1b33",
                        "on-surface-variant": "#40495e",
                        "inverse-surface": "#061a44",
                        "inverse-on-surface": "#eaf0ff",
                        "outline": "#6b7590",
                        "outline-variant": "#c2cbe0",

                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a",

                        "tertiary": "#00130a",
                        "tertiary-container": "#002b1b",
                        "tertiary-fixed": "#6ffbbe",
                        "tertiary-fixed-dim": "#4edea3",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#009f6e",
                        "on-tertiary-fixed": "#002113",
                        "on-tertiary-fixed-variant": "#005236"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "space-xl": "2rem",
                        "space-md": "1rem",
                        "space-xs": "0.25rem",
                        "margin-desktop": "3rem",
                        "margin-tablet": "2rem",
                        "space-2xl": "3rem",
                        "space-2xs": "0.125rem",
                        "space-sm": "0.5rem",
                        "margin": "1rem",
                        "gutter-tablet": "1.5rem",
                        "gutter": "1rem",
                        "gutter-desktop": "2rem",
                        "space-lg": "1.5rem"
                    },
                    "fontFamily": {
                        "label-md": ["Manrope"],
                        "price-display": ["Manrope"],
                        "headline-display": ["\"Source Serif 4\""],
                        "label-sm": ["Manrope"],
                        "headline-display-mobile": ["\"Source Serif 4\""],
                        "headline-md": ["\"Source Serif 4\""],
                        "headline-sm": ["\"Source Serif 4\""],
                        "body-sm": ["Manrope"],
                        "headline-lg-mobile": ["\"Source Serif 4\""],
                        "body-md": ["Manrope"],
                        "label-lg": ["Manrope"],
                        "body-lg": ["Manrope"],
                        "headline-lg": ["\"Source Serif 4\""]
                    },
                    "fontSize": {
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "price-display": ["24px", { "lineHeight": "28px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-display": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "600" }],
                        "label-sm": ["11px", { "lineHeight": "14px", "letterSpacing": "0.04em", "fontWeight": "700" }],
                        "headline-display-mobile": ["30px", { "lineHeight": "36px", "letterSpacing": "-0.015em", "fontWeight": "600" }],
                        "headline-md": ["22px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-sm": ["18px", { "lineHeight": "24px", "fontWeight": "600" }],
                        "body-sm": ["12px", { "lineHeight": "18px", "fontWeight": "500" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-md": ["14px", { "lineHeight": "22px", "fontWeight": "400" }],
                        "label-lg": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "body-lg": ["16px", { "lineHeight": "26px", "fontWeight": "400" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.015em", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
    <style>
        html { -webkit-text-size-adjust: 100%; scroll-behavior: smooth; scroll-padding-top: 5rem; }
        body { overflow-x: hidden; }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }
        .icon-fill { font-variation-settings: 'FILL' 1; }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>

    @stack('head')
    @stack('styles')
</head>
<body class="bg-background text-on-surface antialiased min-h-screen flex flex-col font-body-md text-body-md selection:bg-surface-variant selection:text-primary">

    <a href="#contenu" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:rounded-lg focus:bg-primary focus:text-on-primary">Aller au contenu principal</a>

    @php $isCatalogue = request()->is('catalogue*'); @endphp

    {{-- ===================== HEADER ===================== --}}
    <header class="bg-surface-container-lowest shadow-sm top-0 sticky z-40">
        <div class="flex justify-between items-center w-full px-margin sm:px-space-xl max-w-7xl mx-auto h-16 gap-3">

            {{-- Gauche : logo + recherche (très grand écran) --}}
            <div class="flex items-center gap-space-xl min-w-0">
                <a href="{{ url('/') }}" class="flex items-center flex-shrink-0" aria-label="Tafely Kôrsy — Accueil">
                    <img src="{{ asset('images/logo.png') }}" alt="Tafely Kôrsy" class="h-9 sm:h-10 w-auto" decoding="async">
                </a>

                <div class="relative hidden xl:block w-72">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg" aria-hidden="true">search</span>
                    <input type="search" placeholder="Rechercher un document, cours..." aria-label="Rechercher un document ou un cours"
                           class="w-full pl-9 pr-3 py-1.5 text-body-sm font-body-sm bg-surface rounded border border-outline-variant/60 focus:outline-none focus:border-primary-container text-on-surface placeholder:text-outline transition-colors">
                </div>
            </div>

            {{-- Centre : navigation (à partir de lg) --}}
            <nav class="hidden lg:flex items-center gap-space-lg" aria-label="Navigation principale">
                <a href="{{ url('/catalogue') }}" @if ($isCatalogue) aria-current="page" @endif
                   @class(['py-1 text-label-lg font-label-lg transition-colors', 'text-primary font-bold border-b-2 border-secondary' => $isCatalogue, 'text-on-surface-variant hover:text-primary' => ! $isCatalogue])>Catalogue</a>
                <a href="{{ url('/') }}#comment-ca-marche" class="text-on-surface-variant hover:text-primary py-1 text-label-lg font-label-lg transition-colors">Comment ça marche</a>
                <a href="{{ url('/') }}#vendre" class="text-on-surface-variant hover:text-primary py-1 text-label-lg font-label-lg transition-colors">Espace Vendeur</a>
                @auth
                    <a href="#" class="text-on-surface-variant hover:text-primary py-1 text-label-lg font-label-lg transition-colors">Bibliothèque</a>
                @endauth
            </nav>

            {{-- Droite --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                @guest
                    {{-- Visiteur non connecté : 3 boutons --}}
                    <div class="hidden lg:flex items-center gap-2">
                        <button type="button" data-modal-open="modal-login"
                                class="px-3.5 py-1.5 rounded-lg border border-outline-variant text-primary font-label-md text-label-md hover:bg-surface-container-low transition-colors">
                            Connexion
                        </button>
                        <button type="button" data-modal-open="modal-register-buyer"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors active:scale-[0.98]">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">shopping_bag</span>
                            <span>Acheter un cours</span>
                        </button>
                        <button type="button" data-modal-open="modal-register-seller"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md hover:bg-secondary-container transition-colors active:scale-[0.98]">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">add_circle</span>
                            <span>Vendre un cours</span>
                        </button>
                    </div>

                    {{-- Mobile / tablette : seul « Connexion » reste visible, le reste est dans le menu --}}
                    <button type="button" data-modal-open="modal-login"
                            class="lg:hidden px-3 py-1.5 rounded-lg border border-outline-variant text-primary font-label-md text-label-md hover:bg-surface-container-low transition-colors">
                        Connexion
                    </button>
                @endguest

                @auth
                    {{-- Utilisateur connecté : notifications + avatar (jamais affichés aux visiteurs) --}}
                    <button type="button" aria-label="Notifications"
                            class="p-2 rounded hover:bg-surface-container-low text-on-surface-variant transition-colors relative">
                        <span class="material-symbols-outlined text-xl" aria-hidden="true">notifications</span>
                        <span class="w-2 h-2 rounded-full bg-secondary absolute top-2 right-2"></span>
                    </button>
                    <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-label-lg text-label-lg flex-shrink-0 cursor-pointer" title="{{ auth()->user()->name }}">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endauth

                {{-- Burger --}}
                <button type="button" id="mobile-menu-button" aria-label="Ouvrir le menu"
                        aria-controls="mobile-menu" aria-expanded="false"
                        class="lg:hidden p-2 rounded hover:bg-surface-container-low text-on-surface-variant transition-colors">
                    <span id="mobile-menu-icon" class="material-symbols-outlined text-xl" aria-hidden="true">menu</span>
                </button>
            </div>
        </div>

        {{-- Menu mobile / tablette --}}
        <div id="mobile-menu" class="hidden lg:hidden absolute inset-x-0 top-full bg-surface-container-lowest border-t border-outline-variant/30 shadow-md max-h-[calc(100vh-4rem)] overflow-y-auto">
            <div class="max-w-7xl mx-auto px-margin sm:px-space-xl py-4 flex flex-col gap-4">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg" aria-hidden="true">search</span>
                    <input type="search" placeholder="Rechercher un document, cours..." aria-label="Rechercher un document ou un cours"
                           class="w-full pl-9 pr-3 py-2 text-body-md font-body-md bg-surface rounded border border-outline-variant/60 focus:outline-none focus:border-primary-container text-on-surface placeholder:text-outline transition-colors">
                </div>

                <nav class="flex flex-col" aria-label="Navigation mobile">
                    <a href="{{ url('/catalogue') }}" @if ($isCatalogue) aria-current="page" @endif
                       @class(['py-3 border-b border-outline-variant/20 text-label-lg font-label-lg flex items-center justify-between transition-colors', 'text-primary font-bold' => $isCatalogue, 'text-on-surface-variant hover:text-primary' => ! $isCatalogue])>
                        <span>Catalogue</span>
                        @if ($isCatalogue)<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>@endif
                    </a>
                    <a href="{{ url('/') }}#comment-ca-marche" class="py-3 border-b border-outline-variant/20 text-on-surface-variant hover:text-primary text-label-lg font-label-lg transition-colors">Comment ça marche</a>
                    <a href="{{ url('/') }}#vendre" class="py-3 text-on-surface-variant hover:text-primary text-label-lg font-label-lg transition-colors">Espace Vendeur</a>
                    @auth
                        <a href="#" class="py-3 border-t border-outline-variant/20 text-on-surface-variant hover:text-primary text-label-lg font-label-lg transition-colors">Bibliothèque</a>
                    @endauth
                </nav>

                @guest
                    <div class="flex flex-col gap-2.5">
                        <button type="button" data-modal-open="modal-register-buyer"
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors active:scale-[0.98]">
                            <span class="material-symbols-outlined text-base" aria-hidden="true">shopping_bag</span>
                            <span>Acheter un cours</span>
                        </button>
                        <button type="button" data-modal-open="modal-register-seller"
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-secondary-container transition-colors active:scale-[0.98]">
                            <span class="material-symbols-outlined text-base" aria-hidden="true">add_circle</span>
                            <span>Vendre un cours</span>
                        </button>
                        <button type="button" data-modal-open="modal-login"
                                class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-lg border border-outline-variant text-primary font-label-lg text-label-lg hover:bg-surface-container-low transition-colors">
                            Connexion
                        </button>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    {{-- ===================== CONTENU ===================== --}}
    <main id="contenu" class="flex-grow" tabindex="-1">
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-primary-deep border-t border-white/10 mt-auto text-on-primary">
        <div class="w-full py-space-2xl px-margin sm:px-space-xl max-w-7xl mx-auto flex flex-col gap-space-lg">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-space-xl pb-space-lg border-b border-white/10">

                {{-- Marque & mission --}}
                <div class="md:col-span-5 flex flex-col gap-space-sm">
                    <a href="{{ url('/') }}" class="inline-flex items-center self-start bg-white rounded-lg px-3 py-2" aria-label="Tafely Kôrsy — Accueil">
                        <img src="{{ asset('images/logo.png') }}" alt="Tafely Kôrsy" class="h-8 w-auto" loading="lazy" decoding="async">
                    </a>
                    <p class="text-body-sm font-body-sm text-primary-fixed-dim max-w-sm">
                        La place de marché académique certifiée de Madagascar. Conçue pour permettre aux enseignants émérites de monétiser leurs travaux et aux élèves de réussir leurs examens d'État avec sérénité.
                    </p>
                    <div class="flex items-center gap-space-sm text-primary-fixed-dim pt-1">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">security</span>
                        <span class="text-label-sm font-label-sm">Transactions sécurisées • Paiement Mobile Money direct</span>
                    </div>
                </div>

                {{-- Colonnes de liens --}}
                <nav class="md:col-span-7 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-lg" aria-label="Pied de page">
                    <div class="flex flex-col gap-space-xs">
                        <span class="text-label-md font-label-md font-bold text-on-primary uppercase tracking-wider mb-1">Plateforme</span>
                        <a href="#" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">À propos</a>
                        <a href="{{ url('/catalogue') }}" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Catalogue Général</a>
                        <a href="{{ url('/catalogue') }}?niveau=terminale" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Annales Baccalauréat</a>
                    </div>
                    <div class="flex flex-col gap-space-xs">
                        <span class="text-label-md font-label-md font-bold text-on-primary uppercase tracking-wider mb-1">Enseignants</span>
                        <a href="{{ url('/') }}#vendre" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Espace Vendeur</a>
                        <a href="{{ url('/') }}#vendre" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Rémunération Vendeurs</a>
                        <a href="#" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Charte Pédagogique</a>
                    </div>
                    <div class="flex flex-col gap-space-xs">
                        <span class="text-label-md font-label-md font-bold text-on-primary uppercase tracking-wider mb-1">Sécurité &amp; Légal</span>
                        <a href="#" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Paiements Sécurisés (Mvola, Orange Money, Airtel Money)</a>
                        <a href="#" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Conditions Générales</a>
                        <a href="{{ url('/') }}#faq" class="text-primary-fixed-dim hover:text-on-primary transition-colors duration-150 text-body-sm font-body-sm">Support &amp; FAQ</a>
                    </div>
                </nav>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-sm pt-space-xs text-body-sm font-body-sm text-primary-fixed-dim">
                <p>© {{ now()->year }} Tafely Kôrsy. Tous droits réservés. L'excellence pédagogique certifiée.</p>
                <div class="flex items-center gap-space-md text-label-sm font-label-sm">
                    <span>Antananarivo, Madagascar</span>
                    <span aria-hidden="true">•</span>
                    <span>Version Web Dédiée</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Modales d'inscription / connexion (visiteurs uniquement) --}}
    @guest
        @include('partials.auth-modals')
    @endguest

    <script>
        (function () {
            var root = document.documentElement;

            /* ---------- Menu mobile ---------- */
            var menuBtn = document.getElementById('mobile-menu-button');
            var menu = document.getElementById('mobile-menu');
            var menuIcon = document.getElementById('mobile-menu-icon');

            function setMenu(open) {
                if (!menuBtn || !menu) return;
                menu.classList.toggle('hidden', !open);
                menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                menuBtn.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
                menuIcon.textContent = open ? 'close' : 'menu';
            }
            if (menuBtn && menu) {
                menuBtn.addEventListener('click', function () {
                    setMenu(menuBtn.getAttribute('aria-expanded') !== 'true');
                });
                menu.querySelectorAll('a').forEach(function (a) {
                    a.addEventListener('click', function () { setMenu(false); });
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') setMenu(false);
                });
                window.matchMedia('(min-width: 1024px)').addEventListener('change', function (e) {
                    if (e.matches) setMenu(false);
                });
            }

            /* ---------- Modales (<dialog>) ---------- */
            function openDialogs() { return document.querySelectorAll('dialog[open]'); }

            function openModal(id) {
                var dlg = document.getElementById(id);
                if (!dlg) return;
                openDialogs().forEach(function (d) { d.close(); });
                if (typeof dlg.showModal === 'function') { dlg.showModal(); } else { dlg.setAttribute('open', ''); }
                root.classList.add('overflow-hidden');
            }

            document.querySelectorAll('dialog[data-modal]').forEach(function (dlg) {
                dlg.addEventListener('close', function () {
                    if (!openDialogs().length) root.classList.remove('overflow-hidden');
                });
            });

            document.addEventListener('click', function (e) {
                var opener = e.target.closest('[data-modal-open]');
                if (opener) {
                    e.preventDefault();
                    setMenu(false);
                    openModal(opener.getAttribute('data-modal-open'));
                    return;
                }

                var closer = e.target.closest('[data-modal-close]');
                if (closer) {
                    var parent = closer.closest('dialog');
                    if (parent) parent.close();
                    return;
                }

                // Clic sur le fond (backdrop) => fermeture
                if (e.target.tagName === 'DIALOG' && e.target.hasAttribute('data-modal')) {
                    e.target.close();
                    return;
                }

                // Afficher / masquer le mot de passe
                var toggle = e.target.closest('[data-toggle-password]');
                if (toggle) {
                    var wrap = toggle.closest('[data-password-field]');
                    var input = wrap && wrap.querySelector('input');
                    var icon = toggle.querySelector('.material-symbols-outlined');
                    if (input) {
                        var show = input.type === 'password';
                        input.type = show ? 'text' : 'password';
                        icon.textContent = show ? 'visibility_off' : 'visibility';
                        toggle.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
                    }
                }
            });

            /* ---------- Version statique : aucun envoi réel des formulaires ---------- */
            document.addEventListener('submit', function (e) {
                var form = e.target.closest('form[data-static-form]');
                if (!form) return;
                e.preventDefault();
                var status = form.querySelector('[data-form-status]');
                if (status) {
                    status.textContent = "Version statique : l'envoi du formulaire sera activé à l'étape suivante.";
                    status.classList.remove('hidden');
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>