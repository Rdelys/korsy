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

    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
        @csrf
        @method('PUT')

        {{-- Nom (RÉEL) --}}
        <div class="space-y-1.5">
            <label for="pf-name" class="{{ $label }}">{{ $isVendeur ? 'Nom complet ou titre académique' : 'Nom complet de l\'apprenant' }} <span class="text-secondary">*</span></label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">account_circle</span>
                <input id="pf-name" name="name" type="text" required autocomplete="name" value="{{ old('name', $user->name) }}" class="{{ $input }}">
            </div>
            @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- E-mail (RÉEL) --}}
        <div class="space-y-1.5">
            <label for="pf-email" class="{{ $label }}">Adresse électronique <span class="text-secondary">*</span></label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">mail</span>
                <input id="pf-email" name="email" type="email" required autocomplete="email" value="{{ old('email', $user->email) }}" class="{{ $input }}">
            </div>
            @error('email') <p class="{{ $errText }}">{{ $message }}</p> @enderror
        </div>

        {{-- STATIQUE : établissement --}}
        <div class="space-y-1.5">
            <label for="pf-school" class="{{ $label }}">{{ $isVendeur ? 'Établissement d\'exercice' : 'Établissement scolaire / Université' }} <span class="text-secondary">*</span></label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">apartment</span>
                <input id="pf-school" type="text" data-static value="Lycée Jean-Joseph Rabearivelo, Antananarivo" class="{{ $input }}">
            </div>
        </div>

        {{-- STATIQUE : série (acheteur) / matière (vendeur) --}}
        <div class="space-y-1.5">
            <label for="pf-level" class="{{ $label }}">{{ $isVendeur ? 'Matière principale enseignée' : 'Série / Niveau d\'études actuel' }} <span class="text-secondary">*</span></label>
            <div class="relative">
                <span class="{{ $ico }}" aria-hidden="true">{{ $isVendeur ? 'menu_book' : 'functions' }}</span>
                <select id="pf-level" data-static class="{{ $input }}">
                    @if ($isVendeur)
                        <option selected>Mathématiques</option>
                        <option>Physique-Chimie</option>
                        <option>SVT</option>
                        <option>Français &amp; Littérature</option>
                        <option>Philosophie</option>
                        <option>Histoire-Géographie</option>
                        <option>Anglais</option>
                        <option>Informatique</option>
                    @else
                        <option selected>Série Scientifique C (Maths &amp; Physiques)</option>
                        <option>Série Scientifique D (SVT &amp; Chimie)</option>
                        <option>Série Littéraire A1 / A2</option>
                        <option>Série Technique / Tertiaire</option>
                        <option>Étudiant Supérieur / Faculté des Sciences</option>
                    @endif
                </select>
            </div>
        </div>

        {{-- STATIQUE : présentation (vendeur uniquement) --}}
        @if ($isVendeur)
            <div class="space-y-1.5 md:col-span-2">
                <label for="pf-bio" class="{{ $label }}">Présentation de l'enseignant</label>
                <textarea id="pf-bio" rows="3" data-static maxlength="400"
                          placeholder="Présentez votre parcours, vos classes et vos méthodes en quelques lignes…"
                          class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-[16px] sm:text-body-md font-body-md text-primary focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container">Enseignant de mathématiques depuis 8 ans, spécialisé dans la préparation au Baccalauréat série C et D.</textarea>
                <p class="text-[12px] text-outline">400 caractères maximum. Visible sur vos fiches et masterclasses.</p>
            </div>
        @endif

        {{-- Téléphone (RÉEL) --}}
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

    <p class="mt-space-lg text-[12px] text-outline flex items-start gap-1.5">
        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">info</span>
        <span>Nom, e-mail et téléphone sont enregistrés. Les autres champs sont présentés à titre de démonstration et seront activés prochainement.</span>
    </p>
</div>