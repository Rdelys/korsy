{{-- SECTION : DEVISE (statique : seul l'Ariary est actif) --}}
<div class="{{ $card }}">
    <div class="{{ $cardHead }}">
        <div>
            <h2 class="text-headline-sm font-headline-sm text-primary">Devise utilisée</h2>
            <p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                {{ $isVendeur
                    ? 'Devise de vos prix de vente, de vos gains et de vos retraits.'
                    : 'Devise de l\'affichage des prix, de votre solde et de vos factures.' }}
            </p>
        </div>
        <span class="material-symbols-outlined text-outline" aria-hidden="true">currency_exchange</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm" role="radiogroup" aria-label="Devise">
        {{-- Ariary : actif --}}
        <label class="flex items-center gap-3 p-space-md rounded-lg border-2 border-primary-container bg-surface-container-low/40 cursor-pointer">
            <input type="radio" name="currency" value="MGA" checked class="text-primary-container focus:ring-primary-container/30">
            <span class="min-w-0">
                <span class="flex items-center gap-1.5 text-label-lg font-label-lg text-primary font-bold">Ariary <span class="text-[10px] font-bold bg-primary text-on-primary px-1.5 py-0.5 rounded">Par défaut</span></span>
                <span class="block text-body-sm font-body-sm text-on-surface-variant">MGA · Ar</span>
            </span>
        </label>
        {{-- Euro / Dollar : bientôt --}}
        <label class="flex items-center gap-3 p-space-md rounded-lg border border-outline-variant/40 opacity-60 cursor-not-allowed" title="Bientôt disponible">
            <input type="radio" name="currency" value="EUR" disabled class="text-primary-container">
            <span class="min-w-0">
                <span class="block text-label-lg font-label-lg text-primary font-bold">Euro</span>
                <span class="block text-body-sm font-body-sm text-on-surface-variant">EUR · € — bientôt</span>
            </span>
        </label>
        <label class="flex items-center gap-3 p-space-md rounded-lg border border-outline-variant/40 opacity-60 cursor-not-allowed" title="Bientôt disponible">
            <input type="radio" name="currency" value="USD" disabled class="text-primary-container">
            <span class="min-w-0">
                <span class="block text-label-lg font-label-lg text-primary font-bold">Dollar US</span>
                <span class="block text-body-sm font-body-sm text-on-surface-variant">USD · $ — bientôt</span>
            </span>
        </label>
    </div>

    <div class="mt-space-md flex items-start gap-2 p-space-sm rounded-lg bg-surface-container border border-outline-variant/30 text-body-sm font-body-sm text-on-surface-variant">
        <span class="material-symbols-outlined text-[18px] text-primary shrink-0" aria-hidden="true">info</span>
        <span>
            Les transactions Mobile Money sont réglées en <strong class="text-primary">Ariary (Ar)</strong>.
            @if ($isVendeur)
                Exemple : un fascicule vendu {{ $money(8000) }} Ar vous rapporte le montant affiché dans « Gains disponibles ».
            @else
                Exemple d'affichage : {{ $money(12500) }} Ar.
            @endif
        </span>
    </div>
</div>

{{-- SECTION B : COMPTES MOBILE MONEY --}}
<div class="{{ $card }}" id="payment-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-outline-variant/20 pb-4 mb-space-lg gap-2">
        <div>
            <h2 class="text-headline-sm font-headline-sm text-primary flex flex-wrap items-center gap-2">
                <span>{{ $isVendeur ? 'Compte de reversement Mobile Money' : 'Moyens de Paiement Mobile Money' }}</span>
                <span class="text-xs font-bold uppercase tracking-wider bg-surface-container px-2 py-0.5 rounded text-primary-container">Fintech Malgache</span>
            </h2>
            <p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                {{ $isVendeur
                    ? 'Recevez vos gains directement par MVola, Orange Money ou Airtel Money.'
                    : 'Achetez instantanément vos cours et fascicules sans carte bancaire internationale.' }}
            </p>
        </div>
        <button type="button" class="inline-flex items-center gap-1 text-label-md font-label-md text-secondary hover:underline self-start">
            <span class="material-symbols-outlined text-[16px]" aria-hidden="true">add_circle</span><span>Ajouter un compte</span>
        </button>
    </div>

    <div class="space-y-space-md">
        @foreach ($operators as $op)
            @php $isActive = $op['key'] === $activeOp; @endphp
            <div @class([
                'p-space-md rounded-lg flex flex-col sm:flex-row sm:items-center justify-between gap-space-md transition-colors',
                'border-2 border-primary-container bg-surface-container-low/40' => $isActive,
                'border border-outline-variant/40 bg-surface-container-lowest hover:border-outline-variant' => ! $isActive,
            ])>
                <div class="flex items-center gap-space-md min-w-0">
                    <div @class([
                        'w-12 h-12 rounded-lg flex items-center justify-center shrink-0',
                        'bg-surface-container-lowest border border-outline-variant/40 shadow-sm' => $isActive,
                        'bg-surface-container' => ! $isActive,
                    ])>
                        <span class="material-symbols-outlined text-[26px] {{ $op['color'] }} {{ $isActive ? 'icon-fill' : '' }}" aria-hidden="true">{{ $op['icon'] }}</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-label-lg font-label-lg text-primary font-bold">{{ $op['name'] }}</span>
                            @if ($isActive)
                                @if ($user->phone_verified_at)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#10b981]/10 text-[#047857] flex items-center gap-1">
                                        <span class="material-symbols-outlined icon-fill text-[13px]" aria-hidden="true">check_circle</span>Vérifié
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-secondary-fixed text-on-secondary-fixed-variant">À vérifier</span>
                                @endif
                                <span class="text-[11px] font-bold bg-primary text-on-primary px-1.5 py-0.5 rounded">Par défaut</span>
                            @else
                                <span class="text-body-sm font-body-sm text-outline">Non associé</span>
                            @endif
                        </div>
                        @if ($isActive)
                            <p class="text-body-sm font-body-sm text-primary font-medium mt-0.5 break-words">Numéro actif : +261 {{ $phoneFmt }} ({{ $user->name }})</p>
                        @else
                            <p class="text-body-sm font-body-sm text-on-surface-variant">{{ $isVendeur ? $op['desc_v'] : $op['desc_c'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                    @if ($isActive)
                        <a href="#info" data-goto-tab="info" class="px-3 py-1.5 rounded text-label-md font-label-md text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">Modifier</a>
                        <button type="button" class="px-3 py-1.5 rounded text-label-md font-label-md bg-secondary text-on-secondary hover:bg-secondary-container transition-colors active:scale-[0.98]">
                            {{ $isVendeur ? 'Retirer mes gains' : 'Recharger' }}
                        </button>
                    @else
                        <button type="button" class="px-3 py-1.5 rounded-lg border border-outline-variant hover:bg-surface-container text-primary text-label-md font-label-md transition-colors">
                            Configurer ({{ $op['hint'] }})
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>