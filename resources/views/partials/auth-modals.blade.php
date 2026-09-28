{{--
    Modale d'authentification UNIQUE, avec :
      - un onglet Connexion / Inscription           (data-auth-tab="login|register")
      - un sélecteur de profil Acheteur / Vendeur    (data-auth-role="client|vendeur")
      - dans l'onglet Connexion : bascule Téléphone Mobile Money / E-mail (data-login-method="phone|email")
      - une étape de vérification OTP (data-auth-step="otp"), affichée après une connexion
        ou une inscription depuis un navigateur non reconnu (voir Auth\LoginController).

    Ouverture :  data-modal-open="modal-auth"
                 + data-auth-mode="login|register" (onglet à ouvrir, facultatif — défaut : login)
                 + data-auth-role="client|vendeur" (profil à présélectionner, facultatif — défaut : client)
    Fermeture :  data-modal-close, clic sur le fond, ou touche Échap.

    Les formulaires soumettent réellement vers les routes Auth\* : en cas d'erreur de
    validation ou d'étape OTP, le contrôleur redirige vers la page précédente avec
    open_modal / auth_tab / auth_step / auth_role en session, et le script du layout
    (voir resources/views/layouts/app.blade.php) rouvre alors la modale au bon endroit.
--}}
@php
    $inputClass = 'flex-1 py-2.5 px-3 text-body-md font-body-md text-on-surface placeholder:text-outline/70 border-0 focus:ring-0 outline-none bg-transparent';
    $fieldWrap  = 'relative flex items-center rounded-lg border border-outline-variant/60 focus-within:border-primary-container focus-within:ring-1 focus-within:ring-primary-container bg-surface-container-lowest overflow-hidden transition-colors';
    $labelClass = 'block text-label-sm font-label-sm text-primary mb-1';
    $linkClass  = 'text-primary font-bold hover:text-secondary transition-colors underline-offset-2 hover:underline';
    $errClass   = 'mt-1 text-label-sm font-label-sm text-error';
    $alertClass = 'flex items-start gap-2 text-body-sm font-body-sm text-on-error-container bg-error-container rounded-lg px-3 py-2.5';

    // Numéro tel qu'il doit apparaître dans le champ (sans le préfixe +261, déjà affiché à part).
    $oldPhoneLocal = old('phone') ? preg_replace('/^\+?261/', '', old('phone')) : '';
@endphp

<dialog id="modal-auth" data-modal aria-labelledby="modal-auth-title"
        class="m-auto p-0 w-[calc(100%-1.5rem)] sm:w-full max-w-[540px] max-h-[92vh] overflow-y-auto rounded-xl bg-surface-container-lowest text-on-surface shadow-2xl border border-outline-variant/30 backdrop:bg-primary-deep/70 backdrop:backdrop-blur-sm">

    {{-- En-tête figé : liseré + logo, restent visibles pendant le défilement du contenu --}}
    <div class="sticky top-0 z-20 bg-surface-container-lowest">
        <div class="h-1.5 w-full bg-gradient-to-r from-primary-container via-secondary to-primary-container" aria-hidden="true"></div>

        <div class="relative flex items-center justify-center py-3 px-space-lg sm:px-space-xl border-b border-outline-variant/20">
            <h2 id="modal-auth-title" class="sr-only">Tafely Kôrsy</h2>
            <img src="{{ asset('images/logo.png') }}" alt="Tafely Kôrsy" class="h-8 w-auto" decoding="async">

            <button type="button" data-modal-close aria-label="Fermer"
                    class="absolute top-1/2 -translate-y-1/2 right-space-lg sm:right-space-xl text-on-surface-variant hover:text-primary hover:bg-surface-container-low p-1.5 rounded-lg transition-colors duration-150 group">
                <span class="material-symbols-outlined text-xl group-hover:rotate-90 transition-transform duration-200" aria-hidden="true">close</span>
            </button>
        </div>
    </div>

    <div class="px-space-lg sm:px-space-xl pt-space-lg pb-space-xl">

        {{-- ============================================================ --}}
        {{-- ÉTAPE « FORMULAIRE » : onglets, profil, connexion/inscription --}}
        {{-- ============================================================ --}}
        <div data-auth-step="form">

            <p class="text-body-sm font-body-sm text-on-surface-variant text-center mb-space-lg" data-auth-subtitle>
                L'excellence pédagogique certifiée &amp; commerce académique
            </p>

            {{-- 1. Onglets Connexion / Inscription --}}
            <div class="relative flex p-1 bg-surface-container-low rounded-lg border border-outline-variant/30 mb-space-lg" role="tablist">
                <button type="button" role="tab" data-auth-tab="login" aria-controls="auth-panel-login" aria-selected="true"
                        class="flex-1 py-2 text-center text-label-lg font-label-lg rounded-lg transition-all duration-200">
                    Connexion
                </button>
                <button type="button" role="tab" data-auth-tab="register" aria-controls="auth-panel-register" aria-selected="false"
                        class="flex-1 py-2 text-center text-label-lg font-label-lg rounded-lg transition-all duration-200">
                    Inscription
                </button>
            </div>

            {{-- 2. Sélecteur de profil --}}
            <div class="mb-space-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Sélectionnez votre profil</span>
                    <span data-auth-role-tag class="text-label-sm font-label-sm text-primary bg-primary-fixed px-2 py-0.5 rounded">Apprenant</span>
                </div>
                <div class="grid grid-cols-2 gap-3" role="radiogroup" aria-label="Profil d'utilisateur">
                    {{-- Acheteur / Étudiant --}}
                    <button type="button" role="radio" aria-checked="true" data-auth-role="client"
                            class="relative flex flex-col text-left p-3 rounded-lg border-2 transition-all duration-150 cursor-pointer">
                        <div class="flex items-center justify-between w-full mb-1.5">
                            <span class="w-7 h-7 rounded bg-primary-fixed flex items-center justify-center text-primary-container">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">school</span>
                            </span>
                            <span data-auth-role-check="client" class="w-4 h-4 rounded-full bg-primary-container items-center justify-center text-white flex">
                                <span class="material-symbols-outlined text-[13px] font-bold" aria-hidden="true">check</span>
                            </span>
                        </div>
                        <span class="text-label-md font-label-md font-bold text-primary">Acheteur / Étudiant</span>
                        <span class="text-body-sm font-body-sm text-on-surface-variant text-[11px] leading-tight mt-0.5">Accéder aux cours, fiches PDF certifiées et vidéos</span>
                    </button>

                    {{-- Vendeur / Enseignant --}}
                    <button type="button" role="radio" aria-checked="false" data-auth-role="vendeur"
                            class="relative flex flex-col text-left p-3 rounded-lg border-2 transition-all duration-150 cursor-pointer">
                        <div class="flex items-center justify-between w-full mb-1.5">
                            <span class="w-7 h-7 rounded bg-secondary-fixed flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">co_present</span>
                            </span>
                            <span data-auth-role-check="vendeur" class="hidden w-4 h-4 rounded-full bg-secondary items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[13px] font-bold" aria-hidden="true">check</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-label-md font-label-md font-bold text-primary">Vendeur Pro</span>
                            <span class="material-symbols-outlined icon-fill text-xs text-secondary" aria-hidden="true">verified</span>
                        </div>
                        <span class="text-body-sm font-body-sm text-on-surface-variant text-[11px] leading-tight mt-0.5">Publier, monétiser vos documents et masterclasses</span>
                    </button>
                </div>
            </div>

            {{-- 3. Onglet Connexion --}}
            <div id="auth-panel-login" data-auth-panel="login" class="space-y-4">

                @error('credentials')
                    <div class="{{ $alertClass }}">
                        <span class="material-symbols-outlined text-lg flex-shrink-0" aria-hidden="true">error</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                @error('otp')
                    <div class="{{ $alertClass }}">
                        <span class="material-symbols-outlined text-lg flex-shrink-0" aria-hidden="true">error</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                {{-- Bascule Téléphone / E-mail --}}
                <div class="flex items-center gap-2 p-1 bg-surface-container rounded-lg border border-outline-variant/30 text-body-sm font-body-sm">
                    <button type="button" data-login-method="phone" aria-selected="true"
                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-label-md font-label-md transition-colors">
                        <span class="material-symbols-outlined text-base" aria-hidden="true">smartphone</span>
                        <span>Numéro Mobile</span>
                    </button>
                    <button type="button" data-login-method="email" aria-selected="false"
                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-label-md font-label-md transition-colors">
                        <span class="material-symbols-outlined text-base" aria-hidden="true">alternate_email</span>
                        <span>E-mail &amp; mot de passe</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="role" data-auth-role-input value="{{ old('role', session('auth_role', 'client')) }}">

                    {{-- Téléphone --}}
                    <div data-login-field="phone">
                        <label for="login-phone" class="{{ $labelClass }}">Numéro Mobile Money (Madagascar)</label>
                        <div class="{{ $fieldWrap }}">
                            <span class="inline-flex items-center px-3 bg-surface-container-low border-r border-outline-variant/40 text-label-md font-label-md text-primary font-bold">+261</span>
                            <input id="login-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel-national" placeholder="34 00 000 00" value="{{ $oldPhoneLocal }}" class="{{ $inputClass }}">
                            <span class="pr-3 text-label-sm font-label-sm text-on-surface-variant whitespace-nowrap hidden sm:inline">MVola · Orange · Airtel</span>
                        </div>
                    </div>

                    {{-- E-mail --}}
                    <div data-login-field="email" class="hidden">
                        <label for="login-email" class="{{ $labelClass }}">Adresse électronique</label>
                        <div class="{{ $fieldWrap }}">
                            <span class="pl-3 text-outline material-symbols-outlined text-lg" aria-hidden="true">mail</span>
                            <input id="login-email" name="email" type="email" autocomplete="email" placeholder="votre.nom@institution.mg" value="{{ old('email') }}" class="{{ $inputClass }}">
                        </div>
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="login-password" class="text-label-sm font-label-sm text-primary">Mot de passe</label>
                            <a href="#" class="text-label-sm font-label-sm text-secondary hover:underline">Mot de passe oublié ?</a>
                        </div>
                        <div class="{{ $fieldWrap }}" data-password-field>
                            <span class="pl-3 text-outline material-symbols-outlined text-lg" aria-hidden="true">lock</span>
                            <input id="login-password" name="password" type="password" autocomplete="current-password" placeholder="••••••••••••" class="{{ $inputClass }}">
                            <button type="button" data-toggle-password aria-label="Afficher le mot de passe" class="p-2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">visibility</span>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-body-sm font-body-sm text-on-surface-variant">
                        <input type="checkbox" name="remember" value="1" class="rounded border-outline-variant text-primary-container focus:ring-primary-container/30">
                        <span>Rester connecté sur ce navigateur</span>
                    </label>

                    <button type="submit" class="w-full mt-1 py-2.5 px-space-md bg-secondary hover:bg-secondary-container active:scale-[0.99] text-on-secondary font-label-lg text-label-lg rounded-lg transition-all duration-150 flex items-center justify-center gap-2 shadow-sm">
                        <span>Se connecter</span>
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </button>
                </form>

                {{-- Connexion rapide --}}
                <div class="relative my-4 text-center">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true"><div class="w-full border-t border-outline-variant/30"></div></div>
                    <span class="relative bg-surface-container-lowest px-3 text-label-sm font-label-sm text-outline">ou connexion instantanée</span>
                </div>
                <button type="button" disabled title="Bientôt disponible"
                        class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg border border-outline-variant/60 opacity-60 cursor-not-allowed text-label-md font-label-md text-primary">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.4 1 3.5 3.6 1.6 7.4l3.7 2.9C6.2 7.1 8.9 5 12 5z" fill="#EA4335"></path>
                        <path d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.8z" fill="#4285F4"></path>
                        <path d="M5.3 14.7c-.2-.7-.4-1.5-.4-2.4 0-.8.2-1.6.4-2.4L1.6 7.4C.6 9.4 0 11.6 0 14s.6 4.6 1.6 6.6l3.7-3.9z" fill="#FBBC05"></path>
                        <path d="M12 23c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3.1 0-5.8-2.1-6.7-5.3L1.6 16c1.9 3.8 5.8 7 10.4 7z" fill="#34A853"></path>
                    </svg>
                    <span>Continuer avec Google (bientôt)</span>
                </button>
            </div>

            {{-- 4. Onglet Inscription --}}
            <div id="auth-panel-register" data-auth-panel="register" class="space-y-3.5 hidden">

                {{-- Bandeau garantie vendeur (visible seulement si profil « Vendeur Pro ») --}}
                <div data-auth-vendeur-banner class="hidden p-2.5 bg-surface-container-low rounded-lg border border-outline-variant/30 flex items-start gap-2.5">
                    <span class="material-symbols-outlined icon-fill text-primary-container text-lg shrink-0 mt-0.5" aria-hidden="true">verified_user</span>
                    <p class="text-[11px] leading-relaxed text-on-surface-variant font-body-sm">
                        <strong class="text-primary font-label-sm block">Rémunération garantie pour vendeurs :</strong>
                        Validation rapide de votre profil enseignant et reversement direct par <span class="font-bold text-primary">MVola</span>, <span class="font-bold text-primary">Orange Money</span> ou <span class="font-bold text-primary">Airtel Money</span>.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="role" data-auth-role-input value="{{ old('role', session('auth_role', 'client')) }}">

                    <div>
                        <label for="reg-fullname" class="{{ $labelClass }}">Nom complet ou titre académique</label>
                        <div class="{{ $fieldWrap }}">
                            <span class="pl-3 text-outline material-symbols-outlined text-lg" aria-hidden="true">badge</span>
                            <input id="reg-fullname" name="name" type="text" required autocomplete="name" placeholder="Ex. Prof. Solofo Randriamahaly" value="{{ old('name') }}" class="{{ $inputClass }}">
                        </div>
                        @error('name') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="reg-phone" class="text-label-sm font-label-sm text-primary">Numéro Mobile Money <span class="font-normal text-on-surface-variant">(requis)</span></label>
                            <span class="text-[10px] text-secondary font-bold uppercase">MVola / Orange / Airtel</span>
                        </div>
                        <div class="{{ $fieldWrap }}">
                            <span class="inline-flex items-center px-3 bg-surface-container-low border-r border-outline-variant/40 text-label-md font-label-md text-primary font-bold">+261</span>
                            <input id="reg-phone" name="phone" type="tel" inputmode="tel" required autocomplete="tel-national" placeholder="34 12 345 67" value="{{ $oldPhoneLocal }}" class="{{ $inputClass }}">
                        </div>
                        @error('phone') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="reg-email" class="{{ $labelClass }}">Courriel de correspondance</label>
                        <div class="{{ $fieldWrap }}">
                            <span class="pl-3 text-outline material-symbols-outlined text-lg" aria-hidden="true">mail</span>
                            <input id="reg-email" name="email" type="email" required autocomplete="email" placeholder="contact@domaine.mg" value="{{ old('email') }}" class="{{ $inputClass }}">
                        </div>
                        @error('email') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="reg-password" class="{{ $labelClass }}">Mot de passe sécurisé</label>
                        <div class="{{ $fieldWrap }}" data-password-field>
                            <span class="pl-3 text-outline material-symbols-outlined text-lg" aria-hidden="true">lock</span>
                            <input id="reg-password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="8 caractères min. (lettres, chiffres)" class="{{ $inputClass }}">
                            <button type="button" data-toggle-password aria-label="Afficher le mot de passe" class="p-2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">visibility</span>
                            </button>
                        </div>
                        @error('password') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex items-start gap-2.5 cursor-pointer pt-1">
                        <input type="checkbox" name="terms" required class="mt-1 h-4 w-4 rounded border-outline-variant text-primary-container focus:ring-primary-container/20">
                        <span class="text-body-sm font-body-sm text-on-surface-variant text-[11px] leading-tight">
                            J'accepte sans réserve les <a href="#" class="{{ $linkClass }}">Conditions Générales</a>, la politique de protection des droits d'auteur et la <a href="#" class="{{ $linkClass }}">Charte Pédagogique</a> de Tafely Kôrsy.
                        </span>
                    </label>
                    @error('terms') <p class="{{ $errClass }}">{{ $message }}</p> @enderror

                    <button type="submit" class="w-full mt-1 py-2.5 px-space-md bg-primary-container hover:bg-primary active:scale-[0.99] text-on-primary font-label-lg text-label-lg rounded-lg transition-all duration-150 flex items-center justify-center gap-2 shadow-sm">
                        <span>Créer mon compte</span>
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">verified</span>
                    </button>
                </form>
            </div>

            {{-- 5. Pied de modale --}}
            <p class="mt-space-lg pt-3 border-t border-outline-variant/30 text-body-sm font-body-sm text-on-surface-variant text-center sm:text-left" data-auth-footer-switch>
                <span>Nouveau sur Tafely Kôrsy ?</span>
                <button type="button" data-auth-tab="register" class="text-secondary font-bold hover:underline ml-1">Créer un compte</button>
            </p>
        </div>

        {{-- ============================================================ --}}
        {{-- ÉTAPE « OTP » : code de vérification envoyé par e-mail       --}}
        {{-- ============================================================ --}}
        <div id="auth-panel-otp" data-auth-step="otp" class="hidden text-center">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary-fixed text-primary-container mb-space-md">
                <span class="material-symbols-outlined text-3xl" aria-hidden="true">mark_email_read</span>
            </span>
            <h3 class="text-headline-sm font-headline-sm text-primary mb-1">Vérifiez votre boîte mail</h3>
            <p class="text-body-sm font-body-sm text-on-surface-variant mb-space-lg">
                Nouveau navigateur détecté. Un code à 6 chiffres a été envoyé à
                <span class="font-bold text-primary">{{ session('otp_email_masked', 'votre adresse e-mail') }}</span>.
                Il est valable 10 minutes.
            </p>

            @error('code')
                <div class="{{ $alertClass }} mb-space-md text-left">
                    <span class="material-symbols-outlined text-lg flex-shrink-0" aria-hidden="true">error</span>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            @if (session('status'))
                <div class="flex items-start gap-2 text-body-sm font-body-sm text-primary bg-primary-fixed rounded-lg px-3 py-2.5 mb-space-md text-left">
                    <span class="material-symbols-outlined text-lg flex-shrink-0" aria-hidden="true">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.otp.verify') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="otp-code" class="sr-only">Code de vérification</label>
                    <input id="otp-code" name="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code"
                           placeholder="••••••"
                           class="w-full max-w-[220px] mx-auto block text-center text-headline-md font-headline-md tracking-[0.5em] py-3 rounded-lg border border-outline-variant/60 focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container bg-surface-container-lowest text-primary">
                </div>

                <button type="submit" class="w-full py-2.5 px-space-md bg-primary-container hover:bg-primary active:scale-[0.99] text-on-primary font-label-lg text-label-lg rounded-lg transition-all duration-150 flex items-center justify-center gap-2 shadow-sm">
                    <span>Vérifier le code</span>
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">check</span>
                </button>
            </form>

            <div class="flex items-center justify-center gap-4 mt-space-md text-label-sm font-label-sm">
                <form method="POST" action="{{ route('login.otp.resend') }}">
                    @csrf
                    <button type="submit" class="text-primary font-bold hover:underline">Renvoyer le code</button>
                </form>
                <span class="text-outline-variant" aria-hidden="true">•</span>
                <a href="{{ route('login.otp.cancel') }}" class="text-on-surface-variant hover:text-secondary">Utiliser un autre compte</a>
            </div>
        </div>

        {{-- Toujours visible, quelle que soit l'étape --}}
        <div class="flex items-center justify-center gap-3 text-label-sm font-label-sm text-outline mt-space-lg">
            <a href="{{ url('/') }}#faq" class="hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm" aria-hidden="true">help</span>
                <span>Support &amp; FAQ</span>
            </a>
        </div>
    </div>
</dialog>