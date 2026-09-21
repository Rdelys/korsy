@extends('layouts.app')

{{-- ============================ SEO ============================ --}}
@section('title', "Tafely Kôrsy | Cours PDF & vidéos certifiés à Madagascar")
@section('meta_description', "Achetez cours, annales du Bac et vidéos d'enseignants certifiés à Madagascar, payés par MVola, Orange Money ou Airtel Money. Enseignants : vendez vos cours.")

@php
    // FAQ : utilisée à la fois pour l'affichage et pour les données structurées (JSON-LD FAQPage)
    $faqs = [
        [
            'q' => "Comment acheter un cours ou un document sur Tafely Kôrsy ?",
            'a' => "Créez votre compte gratuit, choisissez le document, la vidéo ou le pack qui vous convient, puis réglez par Mobile Money. Le téléchargement est disponible immédiatement après le paiement.",
        ],
        [
            'q' => "Quels moyens de paiement sont acceptés ?",
            'a' => "Vous pouvez payer par MVola, Orange Money et Airtel Money, directement depuis votre téléphone, en Ariary.",
        ],
        [
            'q' => "Puis-je consulter mes documents et vidéos hors-ligne ?",
            'a' => "Oui. Vos ressources se téléchargent immédiatement et peuvent être consultées hors-ligne, sans connexion internet.",
        ],
        [
            'q' => "Qui publie les contenus de la plateforme ?",
            'a' => "Les cours, fascicules de révision et vidéos sont partagés par des enseignants et des professionnels certifiés à Madagascar.",
        ],
        [
            'q' => "Quels types de ressources trouve-t-on sur Tafely Kôrsy ?",
            'a' => "Des documents et fiches PDF (synthèses, annales corrigées, fiches récapitulatives), des vidéos et masterclasses, ainsi que des packs d'examen complets regroupant cours, fiches mémo et sujets.",
        ],
        [
            'q' => "Comment devenir vendeur et publier mes cours ?",
            'a' => "Cliquez sur « Vendre un cours », créez votre compte vendeur en indiquant votre domaine d'expertise et votre numéro Mobile Money, puis publiez vos documents et vidéos en fixant vous-même leur tarif.",
        ],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '#organisation',
                'name' => 'Tafely Kôrsy',
                'url' => url('/'),
                'logo' => asset('images/logo.png'),
                'description' => "Plateforme malgache de vente de cours, documents PDF et vidéos d'enseignants et de professionnels certifiés.",
                'areaServed' => 'MG',
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#site',
                'url' => url('/'),
                'name' => 'Tafely Kôrsy',
                'inLanguage' => 'fr',
                'publisher' => ['@id' => url('/') . '#organisation'],
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn ($f) => [
                    '@type' => 'Question',
                    'name' => $f['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
                ], $faqs),
            ],
        ],
    ];
@endphp

@push('head')
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')

    {{-- ============================ 1. HERO ============================ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-deep via-primary to-primary-container text-on-primary" aria-labelledby="hero-title">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
             style="background-image: radial-gradient(circle at 15% 20%, rgba(255,255,255,.14) 0, transparent 38%), radial-gradient(circle at 90% 80%, rgba(200,16,46,.40) 0, transparent 34%);"></div>

        <div class="relative max-w-7xl mx-auto px-margin sm:px-space-xl py-10 md:py-16 lg:py-20 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            {{-- Texte + actions --}}
            <div class="lg:col-span-7 text-center lg:text-left">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-on-primary text-label-sm font-label-sm mb-6 text-left">
                    <span class="material-symbols-outlined text-sm text-secondary-fixed-dim flex-shrink-0" aria-hidden="true">verified</span>
                    <span>Documents &amp; vidéos certifiés par des enseignants</span>
                </div>

                <h1 id="hero-title" class="text-headline-display-mobile md:text-headline-display font-headline-display tracking-tight leading-tight mb-4">
                    Apprenez avec les meilleurs <span class="text-secondary-fixed-dim">cours et vidéos</span> d'experts de Madagascar.
                </h1>

                <p class="text-body-md md:text-body-lg font-body-lg text-primary-fixed max-w-2xl mx-auto lg:mx-0 mb-7">
                    Annales du Bac, fiches de révision, cours universitaires et masterclasses partagés par des enseignants et professionnels certifiés. Payez simplement par MVola, Orange Money ou Airtel Money.
                </p>

                {{-- CTA principaux --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3 mb-3">
                    <button type="button" data-modal-open="modal-register-buyer"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-surface-container-lowest text-primary font-label-lg text-label-lg shadow-lg hover:bg-primary-fixed transition-colors active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">shopping_bag</span>
                        <span>Créer mon compte gratuit</span>
                    </button>
                    <button type="button" data-modal-open="modal-register-seller"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg shadow-lg hover:bg-secondary-container transition-colors active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">add_circle</span>
                        <span>Vendre mes cours</span>
                    </button>
                </div>
                <p class="text-body-sm font-body-sm text-primary-fixed-dim mb-8">Inscription gratuite · Paiement à l'unité · Téléchargement immédiat</p>

                {{-- Recherche --}}
                <form action="#" method="GET" role="search"
                      class="max-w-2xl mx-auto lg:mx-0 bg-surface-container-lowest p-1.5 rounded-xl shadow-lg flex items-center gap-1 sm:gap-2 mb-4 focus-within:ring-2 focus-within:ring-secondary-fixed-dim transition">
                    <span class="material-symbols-outlined pl-2 sm:pl-3 text-outline" aria-hidden="true">search</span>
                    <label for="hero-search" class="sr-only">Rechercher un cours ou un document</label>
                    <input id="hero-search" type="search" name="q"
                           placeholder="Rechercher par matière, mot-clé, examen (ex. Baccalauréat, Droit)..."
                           class="w-full min-w-0 py-2.5 text-body-md font-body-md text-on-surface placeholder:text-outline bg-transparent border-0 focus:ring-0 focus:outline-none">
                    <button type="submit"
                            class="px-4 sm:px-5 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors active:scale-[0.98] flex items-center gap-1.5 flex-shrink-0">
                        <span>Explorer</span>
                    </button>
                </form>

                {{-- Sujets fréquents (liens internes utiles au SEO) --}}
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 text-label-md font-label-md">
                    <span class="w-full sm:w-auto text-body-sm font-body-sm text-primary-fixed-dim sm:mr-1">Sujets fréquents :</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-on-primary transition-colors">Mathématiques</a>
                    <a href="#" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-on-primary transition-colors">Droit Privé</a>
                    <a href="#" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-on-primary transition-colors">Informatique &amp; Code</a>
                    <a href="#" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-on-primary transition-colors">Économie &amp; Gestion</a>
                    <a href="#" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-on-primary transition-colors">Sciences Physiques</a>
                </div>
            </div>

            {{-- Visuel décoratif (grand écran uniquement) --}}
            <div class="hidden lg:block lg:col-span-5" aria-hidden="true">
                <div class="relative h-[420px] max-w-sm mx-auto">
                    {{-- Carte PDF --}}
                    <div class="absolute top-0 left-0 w-64 -rotate-3 rounded-xl bg-surface-container-lowest text-on-surface shadow-2xl overflow-hidden">
                        <div class="h-28 bg-surface-container-low p-4 flex flex-col justify-between">
                            <span class="self-start inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-lowest border border-outline-variant/40 text-label-sm font-label-sm text-on-surface">
                                <span class="material-symbols-outlined text-xs text-primary-container">picture_as_pdf</span>
                                <span>PDF (48 p.)</span>
                            </span>
                            <span class="material-symbols-outlined text-4xl text-primary-container self-center">functions</span>
                        </div>
                        <div class="p-4">
                            <p class="font-headline-sm text-headline-sm text-primary line-clamp-2">Annales Bac Maths : Analyse &amp; Probabilités</p>
                            <p class="text-body-sm font-body-sm text-on-surface-variant mt-1">Dr. Hery Rakotobe</p>
                            <p class="font-price-display text-price-display text-primary mt-3">3 500 Ar</p>
                        </div>
                    </div>

                    {{-- Carte vidéo --}}
                    <div class="absolute bottom-0 right-0 w-64 rotate-3 rounded-xl bg-primary-deep border border-white/15 text-on-primary shadow-2xl overflow-hidden">
                        <div class="h-28 p-4 flex flex-col justify-between bg-white/5">
                            <span class="self-start inline-flex items-center gap-1 px-2 py-0.5 rounded bg-white/15 text-label-sm font-label-sm">
                                <span class="material-symbols-outlined text-xs">play_circle</span>
                                <span>Vidéo (3h15)</span>
                            </span>
                            <span class="material-symbols-outlined text-4xl text-white/80 self-center">terminal</span>
                        </div>
                        <div class="p-4">
                            <p class="font-headline-sm text-headline-sm line-clamp-2">Masterclass Algorithmique &amp; Résolution de Problèmes</p>
                            <div class="flex items-center justify-between mt-3">
                                <p class="font-price-display text-price-display">8 500 Ar</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-secondary text-on-secondary text-label-sm font-label-sm">
                                    <span class="material-symbols-outlined text-xs">verified</span>
                                    <span>Certifié</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Pastille rouge flottante --}}
                    <div class="absolute top-40 right-2 w-14 h-14 rounded-full bg-secondary text-on-secondary flex items-center justify-center shadow-xl">
                        <span class="material-symbols-outlined text-2xl">download</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ 2. BANDEAU DE CONFIANCE ============================ --}}
    <section class="bg-surface-container-lowest border-b border-outline-variant/40" aria-label="Nos garanties">
        <div class="max-w-7xl mx-auto px-margin sm:px-space-xl py-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-lg bg-primary-fixed text-primary-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined" aria-hidden="true">verified_user</span>
                </span>
                <div>
                    <p class="text-label-lg font-label-lg text-primary">Contenus certifiés</p>
                    <p class="text-body-sm font-body-sm text-on-surface-variant">Par des enseignants et experts</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined" aria-hidden="true">smartphone</span>
                </span>
                <div>
                    <p class="text-label-lg font-label-lg text-primary">Paiement Mobile Money</p>
                    <p class="text-body-sm font-body-sm text-on-surface-variant">MVola, Orange Money, Airtel Money</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-lg bg-primary-fixed text-primary-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined" aria-hidden="true">download</span>
                </span>
                <div>
                    <p class="text-label-lg font-label-lg text-primary">Téléchargement immédiat</p>
                    <p class="text-body-sm font-body-sm text-on-surface-variant">Accès dès votre paiement</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined" aria-hidden="true">offline_pin</span>
                </span>
                <div>
                    <p class="text-label-lg font-label-lg text-primary">Consultation hors-ligne</p>
                    <p class="text-body-sm font-body-sm text-on-surface-variant">Étudiez sans connexion</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ 3. EXPLORER PAR FORMAT ============================ --}}
    <section class="max-w-7xl mx-auto px-margin sm:px-space-xl pt-10 md:pt-14 pb-4" aria-labelledby="formats-title">
        <h2 id="formats-title" class="sr-only">Explorer par format</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-md md:gap-space-lg">

            <article class="bg-surface-container-lowest rounded-xl p-space-lg border border-outline-variant/40 shadow-sm flex items-start gap-4 hover:border-primary-container transition-all">
                <div class="w-12 h-12 rounded-lg bg-primary-fixed text-primary-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl" aria-hidden="true">description</span>
                </div>
                <div>
                    <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Documents &amp; Fiches PDF</h3>
                    <p class="text-body-sm font-body-sm text-on-surface-variant mb-3">Synthèses claires, annales corrigées et fiches récapitulatives prêtes à imprimer.</p>
                    <span class="inline-flex items-center gap-1 text-label-sm font-label-sm text-primary-container font-bold">
                        <span>Téléchargement immédiat</span>
                        <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                    </span>
                </div>
            </article>

            <article class="bg-surface-container-lowest rounded-xl p-space-lg border border-outline-variant/40 shadow-sm flex items-start gap-4 hover:border-secondary transition-all">
                <div class="w-12 h-12 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl" aria-hidden="true">smart_display</span>
                </div>
                <div>
                    <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Vidéos &amp; Masterclasses</h3>
                    <p class="text-body-sm font-body-sm text-on-surface-variant mb-3">Explications pas-à-pas, résolutions d'exercices types et méthodes illustrées.</p>
                    <span class="inline-flex items-center gap-1 text-label-sm font-label-sm text-secondary font-bold">
                        <span>Accès permanent &amp; flexible</span>
                        <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                    </span>
                </div>
            </article>

            <article class="sm:col-span-2 lg:col-span-1 bg-surface-container-lowest rounded-xl p-space-lg border border-outline-variant/40 shadow-sm flex items-start gap-4 hover:border-primary-container transition-all">
                <div class="w-12 h-12 rounded-lg bg-primary-fixed text-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl" aria-hidden="true">folder_special</span>
                </div>
                <div>
                    <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Packs d'Examen Complets</h3>
                    <p class="text-body-sm font-body-sm text-on-surface-variant mb-3">Ensembles structurés regroupant cours, fiches mémo et banques de sujets d'examen.</p>
                    <span class="inline-flex items-center gap-1 text-label-sm font-label-sm text-primary-container font-bold">
                        <span>Formule Tout-en-un</span>
                        <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                    </span>
                </div>
            </article>
        </div>
    </section>

    {{-- ============================ 4. RESSOURCES POPULAIRES ============================ --}}
    <section id="catalogue" class="max-w-7xl mx-auto px-margin sm:px-space-xl pt-10 md:pt-14 pb-12 md:pb-16" aria-labelledby="catalogue-title">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-6 md:mb-8">
            <div>
                <p class="text-label-sm font-label-sm uppercase tracking-wider text-secondary mb-2">Catalogue</p>
                <h2 id="catalogue-title" class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary tracking-tight">Ressources populaires</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Sélectionnées pour leur rigueur et plébiscitées par les apprenants.</p>
            </div>
            <a href="#" class="inline-flex items-center gap-1 text-label-lg font-label-lg text-primary-container font-bold hover:text-secondary transition-colors flex-shrink-0">
                <span>Voir tout le catalogue</span>
                <span class="material-symbols-outlined text-base" aria-hidden="true">trending_flat</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md md:gap-space-lg">

            {{-- Carte 1 : Maths PDF --}}
            <article class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 shadow-sm overflow-hidden flex flex-col hover:border-primary-container hover:shadow-md transition duration-200">
                <div class="relative h-44 bg-surface-container-low p-4 flex flex-col justify-between border-b border-outline-variant/30">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-outline-variant/40">
                            <span class="material-symbols-outlined text-xs text-primary-container" aria-hidden="true">picture_as_pdf</span>
                            <span>PDF (48 p.)</span>
                        </span>
                        <button type="button" aria-label="Ajouter aux favoris" class="text-outline hover:text-secondary transition-colors">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">bookmark_border</span>
                        </button>
                    </div>
                    <div class="text-center py-2">
                        <span class="material-symbols-outlined text-4xl text-primary-container/70" aria-hidden="true">functions</span>
                    </div>
                    <span class="text-label-sm font-label-sm text-outline uppercase tracking-wider">Lycée / Terminale C-D</span>
                </div>
                <div class="p-space-md flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-sm font-headline-sm text-primary mb-1 line-clamp-2">Fascicule Annales Bac Maths : Analyse &amp; Probabilités</h3>
                        <div class="flex items-center gap-1.5 text-body-sm font-body-sm text-on-surface-variant mb-3">
                            <span>Dr. Hery Rakotobe</span>
                            <span class="material-symbols-outlined text-xs text-primary-container" title="Enseignant certifié">verified</span>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-outline-variant/20 flex items-center justify-between gap-2">
                        <div>
                            <span class="text-body-sm font-body-sm text-outline block">Tarif</span>
                            <span class="text-price-display font-price-display text-primary">3 500 Ar</span>
                        </div>
                        <a href="#" class="px-3.5 py-1.5 rounded-lg bg-primary-fixed hover:bg-primary-container hover:text-on-primary text-primary font-label-md text-label-md transition-all">Consulter</a>
                    </div>
                </div>
            </article>

            {{-- Carte 2 : Droit PDF --}}
            <article class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 shadow-sm overflow-hidden flex flex-col hover:border-primary-container hover:shadow-md transition duration-200">
                <div class="relative h-44 bg-surface-container-low p-4 flex flex-col justify-between border-b border-outline-variant/30">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-outline-variant/40">
                            <span class="material-symbols-outlined text-xs text-primary-container" aria-hidden="true">description</span>
                            <span>PDF (62 p.)</span>
                        </span>
                        <button type="button" aria-label="Ajouter aux favoris" class="text-outline hover:text-secondary transition-colors">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">bookmark_border</span>
                        </button>
                    </div>
                    <div class="text-center py-2">
                        <span class="material-symbols-outlined text-4xl text-primary-container/70" aria-hidden="true">gavel</span>
                    </div>
                    <span class="text-label-sm font-label-sm text-outline uppercase tracking-wider">Université / Droit L2-L3</span>
                </div>
                <div class="p-space-md flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-sm font-headline-sm text-primary mb-1 line-clamp-2">Guide Pratique du Droit des Sociétés Commerciales (OHADA)</h3>
                        <div class="flex items-center gap-1.5 text-body-sm font-body-sm text-on-surface-variant mb-3">
                            <span>Me. Sahondra R.</span>
                            <span class="material-symbols-outlined text-xs text-primary-container" title="Juriste certifié">verified</span>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-outline-variant/20 flex items-center justify-between gap-2">
                        <div>
                            <span class="text-body-sm font-body-sm text-outline block">Tarif</span>
                            <span class="text-price-display font-price-display text-primary">6 000 Ar</span>
                        </div>
                        <a href="#" class="px-3.5 py-1.5 rounded-lg bg-primary-fixed hover:bg-primary-container hover:text-on-primary text-primary font-label-md text-label-md transition-all">Consulter</a>
                    </div>
                </div>
            </article>

            {{-- Carte 3 : Masterclass vidéo --}}
            <article class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 shadow-sm overflow-hidden flex flex-col hover:border-primary-container hover:shadow-md transition duration-200">
                <div class="relative h-44 bg-primary-container p-4 flex flex-col justify-between text-on-primary border-b border-outline-variant/30">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded bg-white/20 backdrop-blur-sm text-on-primary text-label-sm font-label-sm">
                            <span class="material-symbols-outlined text-xs" aria-hidden="true">play_circle</span>
                            <span>Vidéo (3h15)</span>
                        </span>
                        <button type="button" aria-label="Ajouter aux favoris" class="text-primary-fixed hover:text-secondary-fixed-dim transition-colors">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">bookmark_border</span>
                        </button>
                    </div>
                    <div class="text-center py-2">
                        <span class="material-symbols-outlined text-4xl text-white/80" aria-hidden="true">terminal</span>
                    </div>
                    <span class="text-label-sm font-label-sm text-primary-fixed uppercase tracking-wider">Développement Web</span>
                </div>
                <div class="p-space-md flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-sm font-headline-sm text-primary mb-1 line-clamp-2">Masterclass Algorithmique &amp; Résolution de Problèmes</h3>
                        <div class="flex items-center gap-1.5 text-body-sm font-body-sm text-on-surface-variant mb-3">
                            <span>Andry Nirina</span>
                            <span class="material-symbols-outlined text-xs text-primary-container" title="Ingénieur certifié">verified</span>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-outline-variant/20 flex items-center justify-between gap-2">
                        <div>
                            <span class="text-body-sm font-body-sm text-outline block">Tarif</span>
                            <span class="text-price-display font-price-display text-primary">8 500 Ar</span>
                        </div>
                        <a href="#" class="px-3.5 py-1.5 rounded-lg bg-primary-fixed hover:bg-primary-container hover:text-on-primary text-primary font-label-md text-label-md transition-all">Consulter</a>
                    </div>
                </div>
            </article>

            {{-- Carte 4 : Pack Physique-Chimie --}}
            <article class="bg-surface-container-lowest rounded-xl border border-outline-variant/40 shadow-sm overflow-hidden flex flex-col hover:border-primary-container hover:shadow-md transition duration-200">
                <div class="relative h-44 bg-surface-container-low p-4 flex flex-col justify-between border-b border-outline-variant/30">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-outline-variant/40">
                            <span class="material-symbols-outlined text-xs text-secondary" aria-hidden="true">folder_zip</span>
                            <span>Pack Cours + Vidéo</span>
                        </span>
                        <button type="button" aria-label="Ajouter aux favoris" class="text-outline hover:text-secondary transition-colors">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">bookmark_border</span>
                        </button>
                    </div>
                    <div class="text-center py-2">
                        <span class="material-symbols-outlined text-4xl text-primary-container/70" aria-hidden="true">biotech</span>
                    </div>
                    <span class="text-label-sm font-label-sm text-outline uppercase tracking-wider">Terminale Scientifique</span>
                </div>
                <div class="p-space-md flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-sm font-headline-sm text-primary mb-1 line-clamp-2">Fiches Essentielles &amp; Vidéos : Physique-Chimie Bacc</h3>
                        <div class="flex items-center gap-1.5 text-body-sm font-body-sm text-on-surface-variant mb-3">
                            <span>Pr. Michel R.</span>
                            <span class="material-symbols-outlined text-xs text-primary-container" title="Enseignant certifié">verified</span>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-outline-variant/20 flex items-center justify-between gap-2">
                        <div>
                            <span class="text-body-sm font-body-sm text-outline block">Tarif</span>
                            <span class="text-price-display font-price-display text-primary">5 000 Ar</span>
                        </div>
                        <a href="#" class="px-3.5 py-1.5 rounded-lg bg-primary-fixed hover:bg-primary-container hover:text-on-primary text-primary font-label-md text-label-md transition-all">Consulter</a>
                    </div>
                </div>
            </article>
        </div>
    </section>

    {{-- ============================ 5. COMMENT ÇA MARCHE (acheteurs) ============================ --}}
    <section id="comment-ca-marche" class="bg-surface-container-lowest border-y border-outline-variant/40" aria-labelledby="how-title">
        <div class="max-w-7xl mx-auto px-margin sm:px-space-xl py-12 md:py-16">
            <div class="max-w-2xl mx-auto text-center mb-10">
                <p class="text-label-sm font-label-sm uppercase tracking-wider text-secondary mb-2">Pour les apprenants</p>
                <h2 id="how-title" class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary tracking-tight">Étudiez efficacement en 3 étapes</h2>
                <p class="text-body-md md:text-body-lg font-body-lg text-on-surface-variant mt-2">De l'inscription au téléchargement, tout se fait depuis votre téléphone.</p>
            </div>

            <ol class="grid grid-cols-1 md:grid-cols-3 gap-space-lg">
                <li class="relative bg-surface rounded-xl p-space-lg border border-outline-variant/40">
                    <span class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-headline-sm text-headline-sm mb-4" aria-hidden="true">1</span>
                    <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Créez votre compte gratuit</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant">Inscrivez-vous en une minute avec votre e-mail et choisissez votre niveau d'études.</p>
                </li>
                <li class="relative bg-surface rounded-xl p-space-lg border border-outline-variant/40">
                    <span class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-headline-sm text-headline-sm mb-4" aria-hidden="true">2</span>
                    <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Choisissez et payez</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant">Trouvez le cours, la fiche ou la vidéo qu'il vous faut et réglez par MVola, Orange Money ou Airtel Money.</p>
                </li>
                <li class="relative bg-surface rounded-xl p-space-lg border border-outline-variant/40">
                    <span class="w-10 h-10 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-headline-sm text-headline-sm mb-4" aria-hidden="true">3</span>
                    <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Téléchargez et révisez</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant">Accédez immédiatement à vos ressources et consultez-les hors-ligne, où que vous soyez.</p>
                </li>
            </ol>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3">
                <button type="button" data-modal-open="modal-register-buyer"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors active:scale-[0.98]">
                    <span>Créer mon compte gratuit</span>
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                </button>
                <button type="button" data-modal-open="modal-login"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 rounded-lg border border-outline-variant text-primary font-label-lg text-label-lg hover:bg-surface-container-low transition-colors">
                    J'ai déjà un compte
                </button>
            </div>
        </div>
    </section>

    {{-- ============================ 6. VENDRE (vendeurs) ============================ --}}
    <section id="vendre" class="relative overflow-hidden bg-primary-deep text-on-primary" aria-labelledby="sell-title">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
             style="background-image: radial-gradient(circle at 92% 12%, rgba(200,16,46,.35) 0, transparent 34%), radial-gradient(circle at 5% 90%, rgba(22,83,201,.45) 0, transparent 40%);"></div>

        <div class="relative max-w-7xl mx-auto px-margin sm:px-space-xl py-12 md:py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">

            <div>
                <p class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-on-secondary text-label-sm font-label-sm uppercase tracking-wider mb-5">
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">school</span>
                    <span>Enseignants &amp; experts</span>
                </p>
                <h2 id="sell-title" class="text-headline-lg-mobile md:text-headline-display font-headline-display tracking-tight leading-tight mb-4">
                    Transformez vos cours en revenus.
                </h2>
                <p class="text-body-md md:text-body-lg font-body-lg text-primary-fixed mb-6 max-w-xl">
                    Vendez vos documents PDF, fiches et vidéos à des apprenants partout à Madagascar. Vous fixez vos tarifs, nous nous occupons de la plateforme et du paiement Mobile Money.
                </p>

                <ol class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-8 text-label-md font-label-md">
                    <li class="inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-white/15 flex items-center justify-center text-label-sm">1</span><span>Inscrivez-vous</span></li>
                    <li class="hidden sm:block text-primary-fixed-dim" aria-hidden="true">→</li>
                    <li class="inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-white/15 flex items-center justify-center text-label-sm">2</span><span>Publiez vos ressources</span></li>
                    <li class="hidden sm:block text-primary-fixed-dim" aria-hidden="true">→</li>
                    <li class="inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center text-label-sm">3</span><span>Encaissez vos ventes</span></li>
                </ol>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" data-modal-open="modal-register-seller"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg shadow-lg hover:bg-secondary-container transition-colors active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">add_circle</span>
                        <span>Devenir vendeur</span>
                    </button>
                    <button type="button" data-modal-open="modal-login"
                            class="inline-flex items-center justify-center px-6 py-3.5 rounded-lg border border-white/30 text-on-primary font-label-lg text-label-lg hover:bg-white/10 transition-colors">
                        Déjà vendeur ? Connexion
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div class="rounded-xl bg-white/5 border border-white/15 p-space-lg backdrop-blur-sm">
                    <span class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-3"><span class="material-symbols-outlined text-primary-fixed" aria-hidden="true">cloud_upload</span></span>
                    <h3 class="text-headline-sm font-headline-sm mb-1">Publiez en quelques minutes</h3>
                    <p class="text-body-sm font-body-sm text-primary-fixed-dim">Déposez vos PDF et vidéos, ajoutez une description et un tarif.</p>
                </div>
                <div class="rounded-xl bg-white/5 border border-white/15 p-space-lg backdrop-blur-sm">
                    <span class="w-10 h-10 rounded-lg bg-secondary/30 flex items-center justify-center mb-3"><span class="material-symbols-outlined text-secondary-fixed-dim" aria-hidden="true">sell</span></span>
                    <h3 class="text-headline-sm font-headline-sm mb-1">Fixez votre prix</h3>
                    <p class="text-body-sm font-body-sm text-primary-fixed-dim">Vous choisissez le tarif, en Ariary, de chacune de vos ressources.</p>
                </div>
                <div class="rounded-xl bg-white/5 border border-white/15 p-space-lg backdrop-blur-sm">
                    <span class="w-10 h-10 rounded-lg bg-secondary/30 flex items-center justify-center mb-3"><span class="material-symbols-outlined text-secondary-fixed-dim" aria-hidden="true">account_balance_wallet</span></span>
                    <h3 class="text-headline-sm font-headline-sm mb-1">Encaissez par Mobile Money</h3>
                    <p class="text-body-sm font-body-sm text-primary-fixed-dim">Recevez vos revenus via MVola, Orange Money ou Airtel Money.</p>
                </div>
                <div class="rounded-xl bg-white/5 border border-white/15 p-space-lg backdrop-blur-sm">
                    <span class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-3"><span class="material-symbols-outlined text-primary-fixed" aria-hidden="true">workspace_premium</span></span>
                    <h3 class="text-headline-sm font-headline-sm mb-1">Valorisez votre expertise</h3>
                    <p class="text-body-sm font-body-sm text-primary-fixed-dim">Un badge « Certifié » rassure les apprenants et renforce votre crédibilité.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ 7. BANDEAU PAIEMENT MOBILE MONEY ============================ --}}
    <section class="max-w-7xl mx-auto px-margin sm:px-space-xl py-12 md:py-16" aria-label="Paiement à Madagascar">
        <div class="bg-surface-container-lowest rounded-xl p-space-md sm:p-space-lg border border-outline-variant/40 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 md:gap-6">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary-container flex-shrink-0">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">lock</span>
                </div>
                <div>
                    <h2 class="text-headline-sm font-headline-sm text-primary">Paiement simple et direct à Madagascar</h2>
                    <p class="text-body-sm font-body-sm text-on-surface-variant">Règlement instantané par MVola, Orange Money &amp; Airtel Money. Téléchargement immédiat et consultation hors-ligne.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 md:flex-shrink-0">
                <span class="px-3 py-1.5 rounded bg-surface border border-outline-variant/40 text-label-sm font-label-sm text-primary font-bold">MVola</span>
                <span class="px-3 py-1.5 rounded bg-surface border border-outline-variant/40 text-label-sm font-label-sm text-primary font-bold">Orange Money</span>
                <span class="px-3 py-1.5 rounded bg-surface border border-outline-variant/40 text-label-sm font-label-sm text-primary font-bold">Airtel Money</span>
            </div>
        </div>
    </section>

    {{-- ============================ 8. FAQ ============================ --}}
    <section id="faq" class="max-w-3xl mx-auto px-margin sm:px-space-xl pb-12 md:pb-16" aria-labelledby="faq-title">
        <div class="text-center mb-8">
            <p class="text-label-sm font-label-sm uppercase tracking-wider text-secondary mb-2">Support &amp; FAQ</p>
            <h2 id="faq-title" class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary tracking-tight">Questions fréquentes</h2>
        </div>

        <div class="flex flex-col gap-3">
            @foreach ($faqs as $faq)
                <details class="group bg-surface-container-lowest rounded-xl border border-outline-variant/40 open:border-primary-container open:shadow-sm transition">
                    <summary class="flex items-center justify-between gap-4 cursor-pointer px-space-md sm:px-space-lg py-4 text-headline-sm font-headline-sm text-primary">
                        <span>{{ $faq['q'] }}</span>
                        <span class="material-symbols-outlined text-primary-container flex-shrink-0 transition-transform group-open:rotate-180" aria-hidden="true">expand_more</span>
                    </summary>
                    <p class="px-space-md sm:px-space-lg pb-5 text-body-md font-body-md text-on-surface-variant">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- ============================ 9. APPEL À L'ACTION FINAL ============================ --}}
    <section class="bg-gradient-to-br from-primary via-primary-container to-primary text-on-primary" aria-labelledby="cta-title">
        <div class="max-w-4xl mx-auto px-margin sm:px-space-xl py-12 md:py-16 text-center">
            <h2 id="cta-title" class="text-headline-lg-mobile md:text-headline-lg font-headline-lg tracking-tight mb-3">Prêt à apprendre ou à partager votre savoir ?</h2>
            <p class="text-body-md md:text-body-lg font-body-lg text-primary-fixed mb-8">Rejoignez Tafely Kôrsy et faites de chaque cours une réussite.</p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3">
                <button type="button" data-modal-open="modal-register-buyer"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-surface-container-lowest text-primary font-label-lg text-label-lg shadow-lg hover:bg-primary-fixed transition-colors active:scale-[0.98]">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">shopping_bag</span>
                    <span>Acheter un cours</span>
                </button>
                <button type="button" data-modal-open="modal-register-seller"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg shadow-lg hover:bg-secondary-container transition-colors active:scale-[0.98]">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">add_circle</span>
                    <span>Vendre un cours</span>
                </button>
                <button type="button" data-modal-open="modal-login"
                        class="inline-flex items-center justify-center px-6 py-3.5 rounded-lg border border-white/40 text-on-primary font-label-lg text-label-lg hover:bg-white/10 transition-colors">
                    Connexion
                </button>
            </div>
        </div>
    </section>

@endsection