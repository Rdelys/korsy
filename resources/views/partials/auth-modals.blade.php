{{--
    Modales d'authentification (éléments <dialog> natifs : focus piégé, fermeture avec Échap, accessibilité incluse).
    Ouverture : n'importe quel élément avec  data-modal-open="modal-login | modal-register-buyer | modal-register-seller"
    Fermeture : data-modal-close, clic sur le fond, ou touche Échap.
    Version STATIQUE : les formulaires n'envoient rien pour l'instant (voir le script du layout).
--}}
@php
    $dialogClass = 'm-auto p-0 w-[calc(100%-1.5rem)] sm:w-full max-w-md max-h-[92vh] overflow-y-auto rounded-xl bg-surface-container-lowest text-on-surface shadow-2xl backdrop:bg-primary-deep/70 backdrop:backdrop-blur-sm';
    $closeClass  = 'absolute top-3 right-3 p-1.5 rounded text-outline hover:text-primary hover:bg-surface-container-low transition-colors';
    $labelClass  = 'block mb-1 text-label-md font-label-md text-on-surface';
    $inputClass  = 'w-full px-3 py-2.5 text-body-md font-body-md bg-surface border border-outline-variant rounded-lg text-on-surface placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-colors';
    $checkClass  = 'mt-0.5 rounded border-outline-variant text-primary-container focus:ring-primary-container';
    $linkClass   = 'text-primary font-bold hover:text-secondary transition-colors underline-offset-2 hover:underline';
@endphp

{{-- ========================= INSCRIPTION VENDEUR ========================= --}}
<dialog id="modal-register-seller" data-modal aria-labelledby="modal-register-seller-title" class="{{ $dialogClass }}">
    <div class="relative p-space-lg sm:p-space-xl border-t-4 border-secondary">
        <button type="button" data-modal-close aria-label="Fermer" class="{{ $closeClass }}">
            <span class="material-symbols-outlined text-xl" aria-hidden="true">close</span>
        </button>

        <div class="w-11 h-11 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-2xl" aria-hidden="true">add_circle</span>
        </div>
        <h2 id="modal-register-seller-title" class="text-headline-md font-headline-md text-primary">Devenez vendeur sur Tafely Kôrsy</h2>
        <p class="text-body-md font-body-md text-on-surface-variant mt-1 mb-5">Publiez vos cours, fiches et vidéos, fixez vos tarifs et recevez vos revenus par Mobile Money.</p>

        <form method="POST" action="#" data-static-form class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="role" value="seller">

            <div>
                <label for="seller-name" class="{{ $labelClass }}">Nom complet</label>
                <input id="seller-name" name="name" type="text" required autocomplete="name" placeholder="Ex. Hery Rakotobe" class="{{ $inputClass }}">
            </div>

            <div>
                <label for="seller-email" class="{{ $labelClass }}">Adresse e-mail</label>
                <input id="seller-email" name="email" type="email" required autocomplete="email" placeholder="nom@exemple.mg" class="{{ $inputClass }}">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div class="sm:col-span-2">
                    <label for="seller-operator" class="{{ $labelClass }}">Opérateur</label>
                    <select id="seller-operator" name="operator" required class="{{ $inputClass }}">
                        <option value="">Choisir…</option>
                        <option value="mvola">MVola</option>
                        <option value="orange_money">Orange Money</option>
                        <option value="airtel_money">Airtel Money</option>
                    </select>
                </div>
                <div class="sm:col-span-3">
                    <label for="seller-phone" class="{{ $labelClass }}">Numéro Mobile Money</label>
                    <input id="seller-phone" name="phone" type="tel" inputmode="tel" required autocomplete="tel" placeholder="034 00 000 00" class="{{ $inputClass }}">
                </div>
            </div>

            <div>
                <label for="seller-domain" class="{{ $labelClass }}">Domaine d'expertise</label>
                <input id="seller-domain" name="domain" type="text" required placeholder="Ex. Mathématiques, Droit OHADA, Informatique…" class="{{ $inputClass }}">
            </div>

            <div>
                <label for="seller-password" class="{{ $labelClass }}">Mot de passe</label>
                <div class="relative" data-password-field>
                    <input id="seller-password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="8 caractères minimum" class="{{ $inputClass }} pr-11">
                    <button type="button" data-toggle-password aria-label="Afficher le mot de passe" class="absolute inset-y-0 right-0 px-3 text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">visibility</span>
                    </button>
                </div>
            </div>

            <label class="flex items-start gap-2.5 text-body-sm font-body-sm text-on-surface-variant">
                <input type="checkbox" name="terms" required class="{{ $checkClass }}">
                <span>J'accepte les <a href="#" class="{{ $linkClass }}">Conditions Générales</a> et les conditions de rémunération des vendeurs.</span>
            </label>

            <p data-form-status role="status" class="hidden text-body-sm font-body-sm text-primary bg-primary-fixed rounded-lg px-3 py-2"></p>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-secondary-container transition-colors active:scale-[0.98]">
                <span>Créer mon compte vendeur</span>
                <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
            </button>
            <p class="text-body-sm font-body-sm text-outline text-center">Notre équipe examine votre profil avant la publication de vos ressources.</p>
        </form>

        <div class="mt-5 pt-4 border-t border-outline-variant/40 flex flex-col sm:flex-row items-center justify-between gap-2 text-body-sm font-body-sm text-on-surface-variant">
            <span>Déjà inscrit ? <button type="button" data-modal-open="modal-login" class="{{ $linkClass }}">Connexion</button></span>
            <span>Vous voulez apprendre ? <button type="button" data-modal-open="modal-register-buyer" class="{{ $linkClass }}">Compte étudiant</button></span>
        </div>
    </div>
</dialog>

{{-- ========================= INSCRIPTION CLIENT ========================= --}}
<dialog id="modal-register-buyer" data-modal aria-labelledby="modal-register-buyer-title" class="{{ $dialogClass }}">
    <div class="relative p-space-lg sm:p-space-xl border-t-4 border-primary-container">
        <button type="button" data-modal-close aria-label="Fermer" class="{{ $closeClass }}">
            <span class="material-symbols-outlined text-xl" aria-hidden="true">close</span>
        </button>

        <div class="w-11 h-11 rounded-lg bg-primary-fixed text-primary-container flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-2xl" aria-hidden="true">shopping_bag</span>
        </div>
        <h2 id="modal-register-buyer-title" class="text-headline-md font-headline-md text-primary">Créez votre compte gratuit</h2>
        <p class="text-body-md font-body-md text-on-surface-variant mt-1 mb-5">Accédez aux cours, annales et vidéos d'enseignants certifiés et payez par Mobile Money.</p>

        <form method="POST" action="#" data-static-form class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="role" value="buyer">

            <div>
                <label for="buyer-name" class="{{ $labelClass }}">Nom complet</label>
                <input id="buyer-name" name="name" type="text" required autocomplete="name" placeholder="Ex. Miora Rasoanaivo" class="{{ $inputClass }}">
            </div>

            <div>
                <label for="buyer-email" class="{{ $labelClass }}">Adresse e-mail</label>
                <input id="buyer-email" name="email" type="email" required autocomplete="email" placeholder="nom@exemple.mg" class="{{ $inputClass }}">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="buyer-phone" class="{{ $labelClass }}">Téléphone <span class="text-outline font-normal">(facultatif)</span></label>
                    <input id="buyer-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="034 00 000 00" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="buyer-level" class="{{ $labelClass }}">Votre niveau</label>
                    <select id="buyer-level" name="level" required class="{{ $inputClass }}">
                        <option value="">Choisir…</option>
                        <option value="college">Collège</option>
                        <option value="lycee">Lycée / Baccalauréat</option>
                        <option value="universite">Université</option>
                        <option value="pro">Formation professionnelle</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="buyer-password" class="{{ $labelClass }}">Mot de passe</label>
                <div class="relative" data-password-field>
                    <input id="buyer-password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="8 caractères minimum" class="{{ $inputClass }} pr-11">
                    <button type="button" data-toggle-password aria-label="Afficher le mot de passe" class="absolute inset-y-0 right-0 px-3 text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">visibility</span>
                    </button>
                </div>
            </div>

            <label class="flex items-start gap-2.5 text-body-sm font-body-sm text-on-surface-variant">
                <input type="checkbox" name="terms" required class="{{ $checkClass }}">
                <span>J'accepte les <a href="#" class="{{ $linkClass }}">Conditions Générales</a>.</span>
            </label>

            <p data-form-status role="status" class="hidden text-body-sm font-body-sm text-primary bg-primary-fixed rounded-lg px-3 py-2"></p>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors active:scale-[0.98]">
                <span>Créer mon compte</span>
                <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-outline-variant/40 flex flex-col sm:flex-row items-center justify-between gap-2 text-body-sm font-body-sm text-on-surface-variant">
            <span>Déjà inscrit ? <button type="button" data-modal-open="modal-login" class="{{ $linkClass }}">Connexion</button></span>
            <span>Enseignant ? <button type="button" data-modal-open="modal-register-seller" class="{{ $linkClass }}">Devenir vendeur</button></span>
        </div>
    </div>
</dialog>

{{-- ========================= CONNEXION (clients & vendeurs) ========================= --}}
<dialog id="modal-login" data-modal aria-labelledby="modal-login-title" class="{{ $dialogClass }}">
    <div class="relative p-space-lg sm:p-space-xl border-t-4 border-primary-container">
        <button type="button" data-modal-close aria-label="Fermer" class="{{ $closeClass }}">
            <span class="material-symbols-outlined text-xl" aria-hidden="true">close</span>
        </button>

        <div class="w-11 h-11 rounded-lg bg-primary-fixed text-primary-container flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-2xl" aria-hidden="true">login</span>
        </div>
        <h2 id="modal-login-title" class="text-headline-md font-headline-md text-primary">Content de vous revoir</h2>
        <p class="text-body-md font-body-md text-on-surface-variant mt-1 mb-5">Connectez-vous à votre compte, que vous soyez étudiant ou vendeur.</p>

        <form method="POST" action="#" data-static-form class="flex flex-col gap-4">
            @csrf

            <div>
                <label for="login-identifier" class="{{ $labelClass }}">E-mail ou téléphone</label>
                <input id="login-identifier" name="identifier" type="text" required autocomplete="username" placeholder="nom@exemple.mg" class="{{ $inputClass }}">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="login-password" class="text-label-md font-label-md text-on-surface">Mot de passe</label>
                    <a href="#" class="text-body-sm font-body-sm {{ $linkClass }}">Mot de passe oublié ?</a>
                </div>
                <div class="relative" data-password-field>
                    <input id="login-password" name="password" type="password" required autocomplete="current-password" placeholder="Votre mot de passe" class="{{ $inputClass }} pr-11">
                    <button type="button" data-toggle-password aria-label="Afficher le mot de passe" class="absolute inset-y-0 right-0 px-3 text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">visibility</span>
                    </button>
                </div>
            </div>

            <label class="flex items-center gap-2.5 text-body-sm font-body-sm text-on-surface-variant">
                <input type="checkbox" name="remember" class="rounded border-outline-variant text-primary-container focus:ring-primary-container">
                <span>Rester connecté</span>
            </label>

            <p data-form-status role="status" class="hidden text-body-sm font-body-sm text-primary bg-primary-fixed rounded-lg px-3 py-2"></p>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors active:scale-[0.98]">
                <span>Se connecter</span>
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-outline-variant/40">
            <p class="text-body-sm font-body-sm text-on-surface-variant text-center mb-3">Pas encore de compte ?</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <button type="button" data-modal-open="modal-register-buyer"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-primary-container text-primary-container font-label-md text-label-md hover:bg-primary-fixed transition-colors">
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">shopping_bag</span>
                    <span>Je veux acheter</span>
                </button>
                <button type="button" data-modal-open="modal-register-seller"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-secondary text-secondary font-label-md text-label-md hover:bg-secondary-fixed transition-colors">
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">add_circle</span>
                    <span>Je veux vendre</span>
                </button>
            </div>
        </div>
    </div>
</dialog>