@php
    use App\Support\ProfileOptions;

    $levelOptions = ProfileOptions::levelsFor($isVendeur);
    $currentLevel = old('level', $user->level);
@endphp

{{-- SECTION A : informations personnelles / professionnelles & contact --}}
<div class="{{ $card }}">
    <div class="{{ $cardHead }}">
        <div>
            <h2 class="text-headline-sm font-headline-sm text-primary">
                {{ $isVendeur ? 'Informations Professionnelles & Contact' : 'Informations Académiques & Contact' }}
            </h2>
            <p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                {{ $isVendeur
                    ? 'Ces informations sont affichées sur votre page d\'enseignant et rassurent vos acheteurs.'
                    : 'Ces informations permettent d\'adapter vos recommandations d\'annales et d\'épreuves.' }}
            </p>
        </div>
        <span class="material-symbols-outlined text-outline" aria-hidden="true">badge</span>
    </div>

    @if ($errors->any())
        <div class="flex items-start gap-2 text-body-sm font-body-sm text-on-error-container bg-error-container rounded-lg px-3 py-2.5 mb-space-lg" role="alert">
            <span class="material-symbols-outlined text-lg flex-shrink-0" aria-hidden="true">error</span>
            <span>Certaines informations sont à corriger (voir les champs ci-dessous).</span>
        </div>
    @endif

    @if (session('status') && session('profile_tab') === 'info')
        <div class="flex items-start gap-2 text-body-sm font-body-sm text-[#047857] bg-[#10b981]/10 rounded-lg px-3 py-2.5 mb-space-lg" role="status">
            <span class="material-symbols-outlined text-lg flex-shrink-0" aria-hidden="true">check_circle</span>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
        @csrf
        @method('PUT')

        {{-- Nom --}}
        <div class="space-y-1.5">
            <label for="pf-name" class="{{ $label }}">{{ $isVendeur ? 'Nom complet ou titre académique' : 'Nom complet de l\'apprenant' }} <span class="text-secondary">*</span></label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">account_circle</span>
                <input id="pf-name" name="name" type="text" required autocomplete="name" value="{{ old('name', $user->name) }}" class="{{ $input }}">
            </div>
            @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- E-mail --}}
        <div class="space-y-1.5">
            <label for="pf-email" class="{{ $label }}">Adresse électronique <span class="text-secondary">*</span></label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">mail</span>
                <input id="pf-email" name="email" type="email" required autocomplete="email" value="{{ old('email', $user->email) }}" class="{{ $input }}">
            </div>
            @error('email') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Intitulé --}}
        <div class="space-y-1.5 md:col-span-2">
            <label for="pf-headline" class="{{ $label }}">{{ $isVendeur ? 'Intitulé professionnel' : 'Objectif / Préparation' }}</label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">workspace_premium</span>
                <input id="pf-headline" name="headline" type="text" maxlength="150"
                       value="{{ old('headline', $user->headline) }}"
                       placeholder="{{ $isVendeur ? 'Ex. Professeur de Mathématiques — Préparation Bac C / D' : 'Ex. Préparation Baccalauréat Scientifique' }}"
                       class="{{ $input }}">
            </div>
            @error('headline') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Établissement --}}
        <div class="space-y-1.5">
            <label for="pf-school" class="{{ $label }}">{{ $isVendeur ? 'Établissement d\'exercice' : 'Établissement scolaire / Université' }}</label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">apartment</span>
                <input id="pf-school" name="school" type="text" maxlength="255" value="{{ old('school', $user->school) }}" class="{{ $input }}">
            </div>
            @error('school') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Série (apprenant) / Matière (vendeur) --}}
        <div class="space-y-1.5">
            <label for="pf-level" class="{{ $label }}">{{ $isVendeur ? 'Matière principale enseignée' : 'Série / Niveau d\'études actuel' }}</label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">{{ $isVendeur ? 'menu_book' : 'functions' }}</span>
                <select id="pf-level" name="level" class="{{ $input }}">
                    <option value="" @selected(! $currentLevel)>— Choisir —</option>
                    @foreach ($levelOptions as $opt)
                        <option value="{{ $opt }}" @selected($currentLevel === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            @error('level') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Ville --}}
        <div class="space-y-1.5 md:col-span-2">
            <label for="pf-city" class="{{ $label }}">Ville</label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">location_on</span>
                <input id="pf-city" name="city" type="text" maxlength="100" value="{{ old('city', $user->city) }}" placeholder="Ex. Antananarivo" class="{{ $input }}">
            </div>
            @error('city') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Présentation (vendeur uniquement) --}}
        @if ($isVendeur)
            <div class="space-y-1.5 md:col-span-2">
                <label for="pf-bio" class="{{ $label }}">Présentation de l'enseignant</label>
                <textarea id="pf-bio" name="bio" rows="3" maxlength="400"
                          placeholder="Présentez votre parcours, vos classes et vos méthodes en quelques lignes…"
                          class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-[16px] sm:text-body-md font-body-md text-primary focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container">{{ old('bio', $user->bio) }}</textarea>
                <p class="text-[12px] text-outline">400 caractères maximum. Visible sur vos fiches et masterclasses.</p>
                @error('bio') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- Téléphone --}}
        <div class="space-y-1.5 md:col-span-2">
            <label for="pf-phone" class="{{ $label }}">
                {{ $isVendeur ? 'Téléphone Mobile Money (reversement des gains)' : 'Téléphone mobile principal (Madagascar)' }} <span class="text-secondary">*</span>
            </label>
            <div class="flex">
                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-outline-variant bg-surface-container text-body-md font-bold text-primary">+261</span>
                <input id="pf-phone" name="phone" type="tel" inputmode="tel" required autocomplete="tel-national" value="{{ $phoneField }}"
                       class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-r-lg text-[16px] sm:text-body-md font-body-md text-primary focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container">
            </div>
            @error('phone') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            <p class="text-[12px] text-outline">
                {{ $isVendeur
                    ? 'Vos gains sont reversés sur ce numéro. Le modifier demande une nouvelle vérification.'
                    : 'Utilisé pour les alertes SMS et la validation transactionnelle rapide.' }}
            </p>
        </div>
    </form>
</div>