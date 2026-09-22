@extends('layouts.app')

{{-- ============================ SEO ============================ --}}
@section('title', "Catalogue : annales du Bac, cours PDF & vidéos | Tafely Kôrsy")
@section('meta_description', "Annales corrigées du Bac, fascicules PDF et cours vidéo d'enseignants certifiés. Filtrez par matière, niveau ou prix et payez par Mobile Money.")

@php
    // Session du Baccalauréat visée : à partir de juillet, on prépare l'année suivante
    $annee = now()->month >= 7 ? now()->year + 1 : now()->year;

    // Chiffres du hero — DONNÉES D'EXEMPLE issues de la maquette : à remplacer par les vraies statistiques
    $stats = [
        ['value' => '98,4 %',   'label' => 'Taux de réussite Bacc',   'accent' => false],
        ['value' => '14 200+',  'label' => 'Candidats formés',        'accent' => false],
        ['value' => '0 carte',  'label' => 'Paiement Mobile direct',  'accent' => true],
    ];

    // Filtres (statique : à alimenter depuis la base de données)
    $matieres = [
        ['value' => 'mathematiques',  'label' => 'Mathématiques',            'count' => 142, 'checked' => true],
        ['value' => 'physique-chimie','label' => 'Physique-Chimie',          'count' => 98,  'checked' => true],
        ['value' => 'svt',            'label' => 'SVT (Sciences de la Vie)', 'count' => 64,  'checked' => false],
        ['value' => 'philosophie',    'label' => 'Philosophie',              'count' => 45,  'checked' => false],
        ['value' => 'francais-malagasy','label' => 'Français & Malagasy',    'count' => 39,  'checked' => false],
    ];
    $niveaux = [
        ['value' => 'terminale', 'label' => 'Terminale (Bacc)',       'checked' => true],
        ['value' => 'premiere',  'label' => 'Première',               'checked' => false],
        ['value' => 'seconde',   'label' => 'Seconde',                'checked' => false],
        ['value' => 'superieur', 'label' => 'Enseignement Supérieur', 'checked' => false],
    ];
    $formats = [
        ['value' => 'pdf',   'label' => 'Fascicule PDF', 'icon' => 'picture_as_pdf', 'checked' => true],
        ['value' => 'video', 'label' => 'Leçon Vidéo',   'icon' => 'play_circle',    'checked' => false],
    ];
    $prix = [
        ['value' => 'gratuit',    'label' => 'Gratuit (Spécimen)',        'checked' => false],
        ['value' => 'moins-5000', 'label' => 'Moins de 5 000 Ar',         'checked' => true],
        ['value' => '5000-15000', 'label' => '5 000 Ar à 15 000 Ar',      'checked' => false],
        ['value' => 'pack',       'label' => 'Pack Complet (> 15 000 Ar)','checked' => false],
    ];
    $filtresActifs = ['Terminale Bacc', 'Mathématiques & Physique', 'Moins de 5 000 Ar'];

    // Ressources (statique : à remplacer par une requête Eloquent paginée)
    $resources = [
        [
            'type' => 'pdf', 'badge' => 'PDF • 48 pages', 'badge_icon' => 'picture_as_pdf', 'tag' => 'Série C & D',
            'category' => 'Mathématiques',
            'title' => 'Annales Officielles Corrigées : Nombres Complexes & Probabilités',
            'excerpt' => '12 sujets types avec méthodologie pas-à-pas et pièges fréquents rédigés pour le Bacc national.',
            'author' => 'Dr. H. Rakotomalala', 'initials' => 'HR', 'rating' => 4.9, 'reviews' => 184, 'price' => 3500,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcBl5xLp5Np1JJwk17zDQwULpXq2VGvwNzivCL5abFIItkKMw2MoIqEuTzWHEB-kou2YcmwlP4_Dty5Oaqi7mzG6YoHqCk0u0sbXQkdsqTinOHbRxplcLazqgROZkKvnX0aKmvwQPsnz_jOp30A0n_Qqc4CTVn5kALVKADirIecLMJChqOEAGxZiYH5meT7NPEjeIaRGCHr-RNpCY1Hj9liihxdrMN79Nm6SVG4V_n2C41d9D0uNc', 'alt' => 'Aperçu Annales Mathématiques Terminale', 'icon' => 'functions',
        ],
        [
            'type' => 'video', 'badge' => 'Vidéo HD • 2h 45m', 'badge_icon' => 'play_circle', 'tag' => 'Série C & Technique',
            'category' => 'Physique-Chimie',
            'title' => 'Masterclass Électromagnétisme & Circuits RLC Amortis',
            'excerpt' => 'Démonstrations expérimentales filmées en laboratoire et résolution détaillée des sujets Bacc 2018-2024.',
            'author' => 'Mme V. Randria', 'initials' => 'VR', 'rating' => 5.0, 'reviews' => 92, 'price' => 4800,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAhvCZ4iuX4x0KaQFhszLwqHpV6K3Ef6NmehUdu8IwXa8g02-hl_axe_nJdaqqPPvS6eFl221xgCoUJfHswUCOqILw5PKlhahuXrrUi_drS7AcoJi6EOyMrIINbAVzGYOP1-UboGnTk3iB_t3ibfh0pNukuHPbLnxIzti8j-XcZ7Q1crnb_66lW05QNzH6sFw_X8V1BljwwX7Bqz6Pu1SaekxGawV07sMjpsI6OHZZZT_1_Y_La6FM', 'alt' => 'Masterclass Mécanique Physique', 'icon' => 'science',
        ],
        [
            'type' => 'pdf', 'badge' => 'PDF • 62 pages', 'badge_icon' => 'picture_as_pdf', 'tag' => 'Toutes Séries (A, C, D)',
            'category' => 'Philosophie',
            'title' => "Fascicule Clé : L'État, La Liberté et La Conscience Morale",
            'excerpt' => "25 plans de dissertation rédigés, citations clés contextualisées et grilles d'évaluation officielles.",
            'author' => 'Prof. J.C. Rajaona', 'initials' => 'JR', 'rating' => 4.8, 'reviews' => 140, 'price' => 2500,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDBtzbW6-7n1SupPEimcNt39AobIKNotfq7hvexXTRYA8QfhhmmWmi4nDPUDp-PjbNEfJqhA_7i1w43USh51kowFQ1VeOeyMUuCmd8UBsrs9frBhdaQK37E-nULbsnhiIblutPvSmeugko1cN8dsgb_zNdqenTOShm-oMnu5FEeruEztDOyUXtlPhYe0fdT5FSBTl6r-uL3qMiwrhr5Ib2wGoy9l6aWRZjpjzZxEfATf-QluSVq3uY', 'alt' => 'Guide de Dissertation Philosophie', 'icon' => 'psychology',
        ],
        [
            'type' => 'pdf', 'badge' => 'PDF • 54 pages', 'badge_icon' => 'picture_as_pdf', 'tag' => 'Série D Spécial',
            'category' => 'Sciences de la Vie & de la Terre',
            'title' => 'Atlas des Schémas de Synthèse : Génétique Mendélienne & Immunologie',
            'excerpt' => 'Tous les schémas muets et légendés incontournables demandés aux épreuves des 10 dernières années.',
            'author' => 'Dr. N. Andriana', 'initials' => 'NA', 'rating' => 4.9, 'reviews' => 210, 'price' => 3000,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCH0B2NkCdwN0lZOFyivjHdljK3FRLeJWtMmKoaqyGN08yq_N4LDUoZby60o547XIpmVGhXNGYn72lq_SEGcFj_V4Mie6v4J-HBUZ_-HSzwjkGl-TXjw_hhTYTTfxGVmr0Q7cD1iVTIcIYWUrv3nFrr3MIKzrdBpU3w4KmNa4w9qvzokczjKDlecoQs9o6SP0jIlJiO9acwOxZDzZoAGMP0344VDUNPC_YOq1Qn01pTI1gZsQjS3EY', 'alt' => 'Schémas SVT Génétique Terminale D', 'icon' => 'biotech',
        ],
        [
            'type' => 'video', 'badge' => 'Vidéo HD • 3h 10m', 'badge_icon' => 'play_circle', 'tag' => 'Série C & D',
            'category' => 'Mathématiques',
            'title' => 'Visualisation 3D : Géométrie Vectorielle & Produit Scalaire',
            'excerpt' => 'Méthode visuelle pour résoudre sans erreur les équations cartésiennes de plans et calculs de distances.',
            'author' => 'Prof. M. Razafy', 'initials' => 'MR', 'rating' => 4.9, 'reviews' => 78, 'price' => 4500,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBMsze7sWCtCAhEBQqGthWibLCU6xdABrt1T-CUYlSkINEWbz0VRiFiZNC_l86TGQQtpvhNmPjO6FiDApP7cB2FpKuv6D28xsmU6WY6cOQOptXY-bx2U-4CLN56Dwa-6KcDvoCyjvtVkyaIQ0jqgTiPNETgtzW0bFLBdZK6QdyfSUc3ofbazalVMIdZ9Bf_PDnArDxX2CdGJvr_OuhQJHxL2tFY9XZMTbpvVeL7e7b9pugN5PekTkg', 'alt' => "Vidéo Géométrie dans l'Espace", 'icon' => 'view_in_ar',
        ],
        [
            'type' => 'pack', 'badge' => 'Bundle • 5 Fascicules', 'badge_icon' => 'auto_stories', 'featured' => true,
            'category' => 'Pack tout-en-un',
            'title' => "Coffret Réussite Bacc {$annee} : Maths, PC, SVT & Philo",
            'excerpt' => "L'intégralité du programme officiel, fiches mémo détachables et accès direct au groupe de révision Telegram certifié.",
            'author' => 'Collectif Tafely Kôrsy', 'initials' => 'TK', 'rating' => 5.0, 'reviews' => 340, 'price' => 9900, 'old_price' => 15000,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC26UjMLFkAZrN0GudmpzQ4HusSv1Cm_kE25WK4TCTTNygTIhyc4SCshexAVi3vSADDqeNcGUKAnddkJnxkAjZ4olCdCg1I--zZtD03jGVkzKfs0piVK6omX5VA-m3xB8-jWSauGqUY-TZ9dhL_HgFdbOLCU53HByEnkc2uaDovlLKmFr8bcFA9g7R6Mj3DKcVBBfMXj2QDD8Wz0diIY8nkFzHSew-rktTA02quOjDCErtSoHgy9OE', 'alt' => "Pack Intégral Bacc {$annee}", 'icon' => 'auto_stories',
        ],
    ];

    // Données structurées (fil d'Ariane + page de collection)
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil',   'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Catalogue', 'item' => url('/catalogue')],
                ],
            ],
            [
                '@type' => 'CollectionPage',
                'name' => "Catalogue des cours, annales et vidéos | Tafely Kôrsy",
                'url' => url('/catalogue'),
                'inLanguage' => 'fr',
                'description' => "Annales corrigées du Bac, fascicules PDF et cours vidéo d'enseignants certifiés à Madagascar.",
                'isPartOf' => ['@type' => 'WebSite', 'name' => 'Tafely Kôrsy', 'url' => url('/')],
            ],
        ],
    ];

    // Classes réutilisées
    $checkClass = 'rounded border-outline text-primary-container focus:ring-primary-container/30';
    $radioClass = 'border-outline text-primary-container focus:ring-primary-container/30';
    $rowClass   = 'flex items-center gap-2 text-body-md font-body-md text-on-surface cursor-pointer select-none';
    $groupTitle = 'text-label-lg font-label-lg text-primary font-bold flex items-center justify-between';
@endphp

@push('head')
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')

    {{-- ============================ 1. HERO ============================ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-deep via-primary to-primary-container text-on-primary" aria-labelledby="catalogue-hero-title">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
             style="background-image: radial-gradient(circle at 15% 20%, rgba(255,255,255,.14) 0, transparent 38%), radial-gradient(circle at 90% 80%, rgba(200,16,46,.40) 0, transparent 34%);"></div>

        <div class="relative max-w-7xl mx-auto px-margin sm:px-space-xl py-10 md:py-space-2xl grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center z-10">

            {{-- Texte --}}
            <div class="lg:col-span-7 flex flex-col items-start gap-space-md">
                <div class="inline-flex items-center gap-space-xs px-space-sm py-1 bg-white/10 border border-white/20 rounded-full text-on-primary text-label-sm font-label-sm uppercase">
                    <span class="material-symbols-outlined icon-fill text-sm text-secondary-fixed-dim" aria-hidden="true">verified</span>
                    <span>Préparation Baccalauréat {{ $annee }}</span>
                </div>

                <h1 id="catalogue-hero-title" class="text-headline-display-mobile md:text-headline-display font-headline-display tracking-tight leading-tight">
                    Préparez votre réussite avec <span class="text-secondary-fixed-dim">l'élite des professeurs certifiés</span>.
                </h1>

                <p class="text-body-md md:text-body-lg font-body-lg text-primary-fixed max-w-2xl leading-relaxed">
                    Accédez instantanément à des fascicules A4 haute résolution, résolutions d'examens nationaux et masterclasses vidéo. Débloquez les documents vérifiés par règlement sécurisé MVola, Orange Money et Airtel Money.
                </p>

                {{-- Chiffres clés --}}
                <div class="grid grid-cols-3 gap-3 sm:gap-space-lg w-full max-w-xl border-y border-white/15 py-space-md">
                    @foreach ($stats as $i => $stat)
                        <div @class(['border-l border-white/15 pl-3 sm:pl-space-md' => $i > 0])>
                            <p class="text-headline-sm sm:text-headline-md font-headline-md font-bold {{ $stat['accent'] ? 'text-secondary-fixed-dim' : 'text-on-primary' }}">{{ $stat['value'] }}</p>
                            <p class="text-label-md font-label-md text-primary-fixed">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Appels à l'action --}}
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-space-md w-full sm:w-auto">
                    <a href="#catalogue-section" class="inline-flex items-center justify-center gap-space-xs bg-secondary hover:bg-secondary-container text-on-secondary px-space-xl py-3 rounded-lg text-label-lg font-label-lg shadow-lg transition-all duration-150 active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">school</span>
                        <span>Explorer le Catalogue Bacc</span>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center gap-space-xs bg-white/10 border border-white/30 hover:bg-white/15 text-on-primary px-space-lg py-3 rounded-lg text-label-lg font-label-lg transition-all duration-150 active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">description</span>
                        <span>Feuilleter un extrait gratuit</span>
                    </a>
                </div>
            </div>

            {{-- Visuel --}}
            <div class="lg:col-span-5">
                <div class="bg-surface-container-lowest p-space-md sm:p-space-lg rounded-xl border border-outline-variant/40 shadow-2xl relative">
                    <div class="relative rounded-lg overflow-hidden border border-outline-variant/30 bg-surface-container">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBu78MtK3kU7YZ7uSvkI_kGdFD2fUbBll_e3gahChEUhjwhwT1Tm2Y-zp-FyBQcBMrxF_2IikShwiQtLk-6ZgOLy1WR3IkGdQZgPLXO9sriYGrv0Gm_2LT6FsshHDVNI092ym8NWwF1bt3UNQr9Upo2BLcWtA1P7ep02T-FxkizXSBEW4Lp6A008i7pjpqfWz8_Lh3e1A2uTJQ59GRyeemwAuQ6g0yrP3QVnZsiKAEl8AiuyrBqLmc" alt="Étudiant préparant les examens" class="w-full h-52 sm:h-64 object-cover" decoding="async">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary-deep/85 via-transparent to-transparent flex items-end p-space-md">
                            <div class="text-on-primary">
                                <span class="text-label-sm font-label-sm px-2 py-0.5 rounded bg-secondary text-on-secondary mb-1 inline-block">Série Scientifique &amp; Littéraire</span>
                                <p class="text-headline-sm font-headline-sm font-bold leading-snug">Pack Ultime Terminale {{ $annee }} : 8 Matières Clés</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-space-md flex flex-wrap items-center justify-between gap-2 text-body-sm font-body-sm text-on-surface-variant">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined icon-fill text-base text-primary-container" aria-hidden="true">verified</span>
                            100&nbsp;% conforme au programme national
                        </span>
                        <span class="font-bold text-primary">Dès 2&nbsp;500 Ar</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ 2. ESPACE CATALOGUE ============================ --}}
    <div id="catalogue-section" class="max-w-7xl mx-auto w-full px-margin sm:px-space-xl py-space-xl">

        {{-- Fil d'Ariane + tri --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-space-md pb-space-lg mb-space-lg border-b border-outline-variant/30">
            <div>
                <nav aria-label="Fil d'Ariane" class="mb-1">
                    <ol class="flex items-center gap-1 text-label-sm font-label-sm text-on-surface-variant">
                        <li><a href="{{ url('/') }}" class="hover:text-primary hover:underline">Accueil</a></li>
                        <li aria-hidden="true"><span class="material-symbols-outlined text-xs">chevron_right</span></li>
                        <li aria-current="page" class="text-primary font-bold">Catalogue Général</li>
                    </ol>
                </nav>
                <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary tracking-tight">Ressources &amp; Modules d'Enseignement</h2>
            </div>

            <div class="flex items-center gap-space-sm sm:shrink-0">
                <label for="sort-select" class="text-label-md font-label-md text-on-surface-variant whitespace-nowrap">Trier par :</label>
                <select id="sort-select" name="tri" form="filters-form"
                        class="min-w-0 flex-1 sm:flex-none bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md font-body-md py-1.5 pl-3 pr-8 text-on-surface focus:outline-none focus:border-primary-container focus:ring-primary-container/30">
                    <option value="pertinence">Pertinence &amp; Taux de réussite</option>
                    <option value="prix-asc">Prix : Croissant</option>
                    <option value="prix-desc">Prix : Décroissant</option>
                    <option value="nouveautes">Nouveautés {{ $annee }}</option>
                    <option value="notes">Mieux notés</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">

            {{-- Fond sombre du tiroir de filtres (mobile / tablette) --}}
            <div id="filters-backdrop" data-filters-close class="hidden lg:hidden fixed inset-0 z-50 bg-primary-deep/60 backdrop-blur-sm" aria-hidden="true"></div>

            {{-- ===================== FILTRES ===================== --}}
            <aside id="filters-panel" aria-label="Filtres de recherche"
                   class="hidden lg:block fixed inset-y-0 left-0 z-[60] w-[88%] max-w-sm overflow-y-auto bg-surface-container-lowest p-space-lg shadow-2xl
                          lg:z-auto lg:inset-y-auto lg:w-auto lg:max-w-none lg:col-span-3 lg:sticky lg:top-20 lg:max-h-[calc(100vh-6rem)] lg:rounded-xl lg:border lg:border-outline-variant/40 lg:shadow-sm">

                <div class="flex items-center justify-between pb-space-md border-b border-outline-variant/30 mb-space-md">
                    <h3 class="text-headline-sm font-headline-sm text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">tune</span>
                        <span>Filtres de recherche</span>
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/catalogue') }}" class="text-label-sm font-label-sm text-secondary hover:underline">Réinitialiser</a>
                        <button type="button" data-filters-close aria-label="Fermer les filtres" class="lg:hidden p-1 rounded text-outline hover:text-primary hover:bg-surface-container-low">
                            <span class="material-symbols-outlined text-xl" aria-hidden="true">close</span>
                        </button>
                    </div>
                </div>

                <form id="filters-form" method="GET" action="{{ url('/catalogue') }}" class="flex flex-col gap-space-lg">

                    {{-- Matières --}}
                    <div role="group" aria-labelledby="f-matieres" class="flex flex-col gap-space-xs">
                        <span id="f-matieres" class="{{ $groupTitle }}">
                            <span>Matières</span>
                            <span class="text-label-sm font-label-sm text-on-surface-variant font-normal">{{ collect($matieres)->where('checked', true)->count() }} actives</span>
                        </span>
                        <div class="mt-space-xs space-y-2">
                            @foreach ($matieres as $m)
                                <label class="{{ $rowClass }}">
                                    <input type="checkbox" name="matieres[]" value="{{ $m['value'] }}" @checked($m['checked']) class="{{ $checkClass }}">
                                    <span class="flex-1">{{ $m['label'] }}</span>
                                    <span class="text-label-sm font-label-sm text-on-surface-variant">({{ $m['count'] }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-outline-variant/30"></div>

                    {{-- Niveau --}}
                    <div role="group" aria-labelledby="f-niveau" class="flex flex-col gap-space-xs">
                        <span id="f-niveau" class="{{ $groupTitle }}">Niveau Académique</span>
                        <div class="mt-space-xs space-y-2">
                            @foreach ($niveaux as $n)
                                <label class="{{ $rowClass }}">
                                    <input type="radio" name="niveau" value="{{ $n['value'] }}" @checked($n['checked']) class="{{ $radioClass }}">
                                    <span @class(['flex-1', 'font-medium' => $n['checked']])>{{ $n['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-outline-variant/30"></div>

                    {{-- Format --}}
                    <div role="group" aria-labelledby="f-format" class="flex flex-col gap-space-xs">
                        <span id="f-format" class="{{ $groupTitle }}">Format de ressource</span>
                        <div class="grid grid-cols-2 gap-2 mt-space-xs">
                            @foreach ($formats as $f)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="format[]" value="{{ $f['value'] }}" @checked($f['checked']) class="peer sr-only">
                                    <span class="flex flex-col items-center justify-center p-2.5 rounded-lg border-2 border-outline-variant/60 bg-surface-container-lowest text-on-surface-variant text-center transition-colors hover:border-primary-container hover:text-primary peer-checked:border-primary-container peer-checked:bg-primary-fixed peer-checked:text-primary peer-focus-visible:ring-2 peer-focus-visible:ring-primary-container/40">
                                        <span class="material-symbols-outlined text-lg mb-1" aria-hidden="true">{{ $f['icon'] }}</span>
                                        <span class="text-label-sm font-label-sm">{{ $f['label'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-outline-variant/30"></div>

                    {{-- Prix --}}
                    <div role="group" aria-labelledby="f-prix" class="flex flex-col gap-space-xs">
                        <span id="f-prix" class="{{ $groupTitle }}">Fourchette de Prix</span>
                        <div class="mt-space-xs space-y-2">
                            @foreach ($prix as $p)
                                <label class="{{ $rowClass }}">
                                    <input type="checkbox" name="prix[]" value="{{ $p['value'] }}" @checked($p['checked']) class="{{ $checkClass }}">
                                    <span class="flex-1">{{ $p['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Garantie --}}
                    <div class="p-3 bg-surface-container-low rounded-lg border border-outline-variant/40 flex items-start gap-2">
                        <span class="material-symbols-outlined icon-fill text-primary-container text-lg shrink-0 mt-0.5" aria-hidden="true">verified_user</span>
                        <p class="text-body-sm font-body-sm text-on-surface">
                            <span class="font-bold">Garantie Tafely :</span> tous les auteurs sont titulaires d'une certification d'enseignement d'État.
                        </p>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors active:scale-[0.98]">
                        <span class="material-symbols-outlined text-base" aria-hidden="true">check</span>
                        <span>Appliquer les filtres</span>
                    </button>
                </form>
            </aside>

            {{-- ===================== RÉSULTATS ===================== --}}
            <section class="lg:col-span-9 min-w-0 flex flex-col gap-space-lg" aria-label="Résultats du catalogue">

                {{-- Bouton d'ouverture des filtres (mobile / tablette) --}}
                <button type="button" data-filters-open aria-controls="filters-panel"
                        class="lg:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest text-primary font-label-lg text-label-lg hover:border-primary-container transition-colors">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">tune</span>
                    <span>Filtres</span>
                    <span class="min-w-[1.25rem] h-5 px-1.5 rounded-full bg-secondary text-on-secondary text-label-sm font-label-sm flex items-center justify-center">{{ count($filtresActifs) }}</span>
                </button>

                {{-- Filtres appliqués --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-label-md font-label-md text-on-surface-variant">Filtres appliqués :</span>
                    @foreach ($filtresActifs as $filtre)
                        <span class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 bg-surface-container rounded-full text-label-sm font-label-sm text-primary border border-outline-variant/30">
                            {{ $filtre }}
                            <a href="#" aria-label="Retirer le filtre {{ $filtre }}" class="hover:text-secondary">
                                <span class="material-symbols-outlined text-xs" aria-hidden="true">close</span>
                            </a>
                        </span>
                    @endforeach
                    <a href="{{ url('/catalogue') }}" class="text-label-sm font-label-sm text-secondary hover:underline ml-2">Effacer tout</a>
                </div>

                {{-- Grille --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-space-lg">
                    @foreach ($resources as $resource)
                        <x-resource-card :r="$resource" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-space-lg border-t border-outline-variant/30">
                    <p class="text-body-sm font-body-sm text-on-surface-variant text-center sm:text-left">
                        Affichage de <span class="font-bold text-primary">1 à 6</span> sur <span class="font-bold text-primary">142</span> ressources disponibles
                    </p>
                    <nav aria-label="Pagination" class="flex items-center gap-1">
                        <span aria-disabled="true" aria-label="Page précédente" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant opacity-40">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_back_ios</span>
                        </span>
                        <a href="{{ url('/catalogue') }}" aria-current="page" class="w-9 h-9 inline-flex items-center justify-center rounded-lg bg-primary-container text-on-primary font-bold text-label-md font-label-md">1</a>
                        <a href="{{ url('/catalogue') }}?page=2" class="w-9 h-9 inline-flex items-center justify-center rounded-lg hover:bg-surface-container-low border border-outline-variant/40 text-on-surface text-label-md font-label-md">2</a>
                        <a href="{{ url('/catalogue') }}?page=3" class="w-9 h-9 inline-flex items-center justify-center rounded-lg hover:bg-surface-container-low border border-outline-variant/40 text-on-surface text-label-md font-label-md">3</a>
                        <span class="px-1 text-outline" aria-hidden="true">…</span>
                        <a href="{{ url('/catalogue') }}?page=12" class="w-9 h-9 inline-flex items-center justify-center rounded-lg hover:bg-surface-container-low border border-outline-variant/40 text-on-surface text-label-md font-label-md">12</a>
                        <a href="{{ url('/catalogue') }}?page=2" aria-label="Page suivante" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-low text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward_ios</span>
                        </a>
                    </nav>
                </div>
            </section>
        </div>
    </div>

    {{-- ============================ 3. BANDEAU MOBILE MONEY ============================ --}}
    <section class="bg-surface-container-low border-y border-outline-variant/30 py-space-xl mt-space-xl" aria-labelledby="checkout-title">
        <div class="max-w-7xl mx-auto px-margin sm:px-space-xl">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 p-space-md sm:p-space-xl shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">

                <div class="lg:col-span-6 flex flex-col gap-space-xs">
                    <div class="flex items-center gap-2 text-secondary text-label-sm font-label-sm font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">bolt</span>
                        <span>Règlement instantané sans carte bancaire</span>
                    </div>
                    <h2 id="checkout-title" class="text-headline-sm sm:text-headline-md font-headline-md text-primary font-bold">
                        Téléchargement immédiat après validation Mobile Money.
                    </h2>
                    <p class="text-body-md font-body-md text-on-surface-variant">
                        Ne perdez plus une minute de révision. Vos documents PDF haute définition et vos codes d'accès vidéo sont délivrés instantanément sur votre compte et par SMS dès la confirmation de votre opérateur.
                    </p>
                </div>

                <div class="lg:col-span-6 grid grid-cols-3 gap-2 sm:gap-space-md">
                    <div class="p-2 sm:p-space-md rounded-xl border-2 border-primary-container bg-surface-container-lowest flex flex-col items-center justify-center text-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#00853f]/10 flex items-center justify-center text-[#00853f] mb-2 font-black text-base sm:text-lg" aria-hidden="true">MV</div>
                        <span class="text-label-md sm:text-label-lg font-label-lg font-bold text-primary">MVola</span>
                        <span class="text-label-sm font-label-sm text-primary-container font-bold flex items-center gap-0.5 mt-0.5">
                            <span class="material-symbols-outlined text-xs" aria-hidden="true">check_circle</span>Actif 24/7
                        </span>
                    </div>
                    <div class="p-2 sm:p-space-md rounded-xl border border-outline-variant/60 bg-surface-container-lowest flex flex-col items-center justify-center text-center hover:border-primary-container transition-colors">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#f16e00]/10 flex items-center justify-center text-[#f16e00] mb-2 font-black text-base sm:text-lg" aria-hidden="true">OM</div>
                        <span class="text-label-md sm:text-label-lg font-label-lg font-bold text-primary">Orange Money</span>
                        <span class="text-label-sm font-label-sm text-primary-container font-bold flex items-center gap-0.5 mt-0.5">
                            <span class="material-symbols-outlined text-xs" aria-hidden="true">check_circle</span>Actif 24/7
                        </span>
                    </div>
                    <div class="p-2 sm:p-space-md rounded-xl border border-outline-variant/60 bg-surface-container-lowest flex flex-col items-center justify-center text-center hover:border-primary-container transition-colors">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#e40000]/10 flex items-center justify-center text-[#e40000] mb-2 font-black text-base sm:text-lg" aria-hidden="true">AM</div>
                        <span class="text-label-md sm:text-label-lg font-label-lg font-bold text-primary">Airtel Money</span>
                        <span class="text-label-sm font-label-sm text-primary-container font-bold flex items-center gap-0.5 mt-0.5">
                            <span class="material-symbols-outlined text-xs" aria-hidden="true">check_circle</span>Actif 24/7
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        /* Tiroir de filtres (mobile / tablette) */
        (function () {
            var panel = document.getElementById('filters-panel');
            var backdrop = document.getElementById('filters-backdrop');
            if (!panel || !backdrop) return;
            var root = document.documentElement;
            var desktop = window.matchMedia('(min-width: 1024px)');

            function setOpen(open) {
                panel.classList.toggle('hidden', !open);
                backdrop.classList.toggle('hidden', !open);
                root.classList.toggle('overflow-hidden', open);
                if (open) { panel.setAttribute('role', 'dialog'); panel.setAttribute('aria-modal', 'true'); }
                else { panel.removeAttribute('role'); panel.removeAttribute('aria-modal'); }
            }

            document.addEventListener('click', function (e) {
                if (e.target.closest('[data-filters-open]')) { setOpen(true); return; }
                if (e.target.closest('[data-filters-close]')) { setOpen(false); }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !panel.classList.contains('hidden') && !desktop.matches) setOpen(false);
            });
            desktop.addEventListener('change', function (e) { if (e.matches) setOpen(false); });
        })();
    </script>
@endpush