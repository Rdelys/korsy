<?php

namespace App\Http\Requests;

use App\Support\NormalizesMalagasyPhone;
use App\Support\ProfileOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    use NormalizesMalagasyPhone;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->normalizePhone($this->input('phone')),
        ]);
    }

    public function rules(): array
    {
        $user = $this->user();
        $id = $user->id;

        $prefixes = collect(config('mobile_money.operators'))
            ->pluck('prefixes')->flatten()->implode('|');

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone' => [
                'required',
                'regex:/^\+261('.$prefixes.')\d{7}$/',
                Rule::unique('users', 'phone')->ignore($id),
            ],
            'headline' => ['nullable', 'string', 'max:150'],
            'school' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', Rule::in(ProfileOptions::levelsFor($user->isVendeur()))],
        ];

        if ($user->isVendeur()) {
            $rules['bio'] = ['nullable', 'string', 'max:400'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Merci d\'indiquer votre nom complet.',
            'email.required' => 'Merci d\'indiquer votre adresse e-mail.',
            'email.email' => 'Cette adresse e-mail ne semble pas valide.',
            'email.unique' => 'Un autre compte utilise déjà cette adresse e-mail.',
            'phone.required' => 'Le numéro Mobile Money est requis.',
            'phone.regex' => 'Numéro invalide. Utilisez un numéro MVola ou Orange Money Malagasy.',
            'phone.unique' => 'Ce numéro est déjà associé à un autre compte.',
            'headline.max' => 'L\'intitulé ne doit pas dépasser 150 caractères.',
            'school.max' => 'Le nom de l\'établissement est trop long.',
            'level.in' => 'Valeur non valide pour ce champ.',
            'bio.max' => 'La présentation ne doit pas dépasser 400 caractères.',
        ];
    }
}