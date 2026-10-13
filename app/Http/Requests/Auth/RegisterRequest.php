<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Support\NormalizesMalagasyPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use NormalizesMalagasyPhone;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalise le téléphone avant la validation, pour que la règle `unique`
     * et l'enregistrement final portent sur la même valeur au format +261…
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->normalizePhone($this->input('phone')),
            'role' => in_array($this->input('role'), [User::ROLE_CLIENT, User::ROLE_VENDEUR], true)
                ? $this->input('role')
                : User::ROLE_CLIENT,
        ]);
    }

    public function rules(): array
    {
        // Préfixes autorisés : MVola + Orange Money (config/mobile_money.php).
        $prefixes = collect(config('mobile_money.operators'))
            ->pluck('prefixes')->flatten()->implode('|');

        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'role' => ['required', Rule::in([User::ROLE_CLIENT, User::ROLE_VENDEUR])],
            'phone' => [
                'required',
                'regex:/^\+261('.$prefixes.')\d{7}$/',
                Rule::unique('users', 'phone'),
            ],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            // Un seul champ mot de passe dans la modale (pas de confirmation) : voir
            // partials/auth-modals.blade.php. Le minimum de 8 caractères est aussi
            // imposé côté client via l'attribut HTML `minlength`.
            'password' => ['required', Password::min(8)],
            'terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Merci d\'indiquer votre nom complet.',
            'phone.required' => 'Le numéro Mobile Money est requis.',
            'phone.regex' => 'Numéro invalide. Utilisez un numéro MVola ou Orange Money malgache.',
            'phone.unique' => 'Ce numéro est déjà associé à un compte.',
            'email.required' => 'Merci d\'indiquer votre adresse e-mail.',
            'email.email' => 'Cette adresse e-mail ne semble pas valide.',
            'email.unique' => 'Un compte existe déjà avec cette adresse e-mail.',
            'password.required' => 'Merci de choisir un mot de passe.',
            'terms.accepted' => 'Vous devez accepter les conditions générales pour continuer.',
        ];
    }
}