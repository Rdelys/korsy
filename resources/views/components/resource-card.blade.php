{{--
    Carte de ressource (PDF, vidéo ou pack) — usage :  <x-resource-card :r="$resource" />

    Clés attendues dans $r :
      type        : 'pdf' | 'video' | 'pack'
      badge       : texte du badge en haut à gauche (ex. « PDF • 48 pages »)
      badge_icon  : icône Material Symbols du badge
      tag         : (option) étiquette en haut à droite (ex. « Série C & D »)
      category    : matière (affichée en majuscules, en rouge)
      title, excerpt
      author, initials, rating, reviews
      price, old_price (option), featured (option, met la carte en avant)
      image, alt  : (option) aperçu. Sans image, un visuel de remplacement est affiché.
      icon        : (option) icône du visuel de remplacement
      url         : (option) lien vers la fiche de la ressource
--}}
@props(['r'])

@php
    $type     = $r['type'] ?? 'pdf';
    $featured = $r['featured'] ?? false;
    $url      = $r['url'] ?? '#';
    $money    = fn ($n) => number_format($n, 0, ',', "\u{00A0}");
    $rating   = number_format($r['rating'], 1, ',', '');
    $promo    = ! empty($r['old_price']);
@endphp

<article @class([
    'relative group rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden',
    'bg-surface-container-lowest border border-outline-variant/40 hover:border-primary-container' => ! $featured,
    'bg-surface-container-low border-2 border-secondary/30 hover:border-secondary/60' => $featured,
])>

    @if ($featured)
        <div class="absolute top-0 right-0 z-10 bg-secondary text-on-secondary text-label-sm font-label-sm px-3 py-1 rounded-bl-lg font-bold tracking-wider">
            RECOMMANDÉ
        </div>
    @endif

    {{-- Aperçu --}}
    <div @class(['relative h-44 overflow-hidden', 'bg-surface-container-low' => ! $featured, 'bg-surface-container' => $featured])>
        @if (! empty($r['image']))
            <img src="{{ $r['image'] }}" alt="{{ $r['alt'] ?? $r['title'] }}" loading="lazy" decoding="async"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-fixed to-surface-container" aria-hidden="true">
                <span class="material-symbols-outlined text-5xl text-primary-container/60">{{ $r['icon'] ?? 'description' }}</span>
            </div>
        @endif

        {{-- Badge de format (haut gauche) --}}
        <div @class([
            'absolute top-2.5 left-2.5 px-2 py-0.5 rounded flex items-center gap-1 text-label-sm font-label-sm shadow-sm whitespace-nowrap',
            'bg-primary-container text-on-primary' => $type === 'video',
            'bg-surface-container-lowest/95 backdrop-blur-sm text-primary border border-outline-variant/40' => $type !== 'video',
        ])>
            <span @class(['material-symbols-outlined text-sm', 'text-on-primary' => $type === 'video', 'text-secondary' => $type !== 'video']) aria-hidden="true">{{ $r['badge_icon'] }}</span>
            <span>{{ $r['badge'] }}</span>
        </div>

        {{-- Étiquette de série / niveau (haut droite) --}}
        @if (! empty($r['tag']))
            <div @class([
                'absolute top-2.5 right-2.5 max-w-[52%] truncate px-2 py-0.5 rounded text-label-sm font-label-sm font-bold',
                'bg-primary-container text-on-primary' => $type === 'pdf',
                'bg-surface-container-lowest/90 backdrop-blur-sm text-primary border border-outline-variant/30' => $type !== 'pdf',
            ])>
                {{ $r['tag'] }}
            </div>
        @endif
    </div>

    {{-- Contenu --}}
    <div class="p-space-md flex-1 flex flex-col justify-between">
        <div>
            <p class="text-label-sm font-label-sm text-secondary font-bold mb-1 uppercase">{{ $r['category'] }}</p>
            <h3 class="text-headline-sm font-headline-sm text-primary leading-snug line-clamp-2 group-hover:text-secondary transition-colors">
                {{-- Lien étendu à toute la carte (le bouton « Acheter » reste cliquable grâce à son z-index) --}}
                <a href="{{ $url }}" class="after:absolute after:inset-0 after:z-[1] focus:outline-none focus-visible:after:ring-2 focus-visible:after:ring-primary-container focus-visible:after:rounded-xl">{{ $r['title'] }}</a>
            </h3>
            <p class="text-body-sm font-body-sm text-on-surface-variant mt-1.5 line-clamp-2">{{ $r['excerpt'] }}</p>
        </div>

        <div class="pt-space-md mt-space-sm border-t border-outline-variant/30">
            {{-- Auteur & note --}}
            <div class="flex items-center justify-between gap-2 mb-space-sm">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-[10px] flex-shrink-0" aria-hidden="true">{{ $r['initials'] }}</span>
                    <div class="flex items-center gap-0.5 min-w-0">
                        <span class="text-label-sm font-label-sm text-primary font-bold truncate">{{ $r['author'] }}</span>
                        <span class="material-symbols-outlined icon-fill text-xs text-primary-container flex-shrink-0" title="Auteur certifié">verified</span>
                    </div>
                </div>
                <div class="flex items-center text-label-sm font-label-sm text-on-surface font-bold flex-shrink-0" title="Note moyenne : {{ $rating }} sur 5">
                    <span class="material-symbols-outlined icon-fill text-sm text-amber-500 mr-0.5" aria-hidden="true">star</span>
                    <span>{{ $rating }}</span>
                    <span class="text-on-surface-variant font-normal ml-0.5">({{ $r['reviews'] }})</span>
                </div>
            </div>

            {{-- Prix & achat --}}
            <div class="flex items-center justify-between gap-2 pt-1">
                <div>
                    @if ($promo)
                        <div class="text-label-sm font-label-sm text-outline uppercase tracking-wider line-through">{{ $money($r['old_price']) }} Ar</div>
                    @else
                        <div class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Prix instantané</div>
                    @endif
                    <div @class(['text-price-display font-price-display font-bold', 'text-secondary' => $promo, 'text-primary' => ! $promo])>{{ $money($r['price']) }} Ar</div>
                </div>

                {{-- Visiteur non connecté : ouvre la modale de connexion --}}
                <button type="button" @guest data-modal-open="modal-login" @endguest
                        aria-label="Acheter : {{ $r['title'] }}"
                        @class([
                            'relative z-[2] text-on-primary px-space-md py-2 rounded-lg text-label-md font-label-md flex items-center gap-1 shadow-sm transition-all duration-150 active:scale-[0.98]',
                            'bg-primary-container hover:bg-primary' => ! $featured,
                            'bg-secondary hover:bg-secondary-container' => $featured,
                        ])>
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">shopping_cart</span>
                    <span>Acheter</span>
                </button>
            </div>
        </div>
    </div>
</article>