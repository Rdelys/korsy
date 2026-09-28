<?php

namespace App\Http\Requests\Auth;

use App\Support\NormalizesMalagasyPhone;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use NormalizesMalagasyPhone;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->filled('phone') ? $this->normalizePhone($this->input('phone')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'phone' => ['required_without:email', 'nullable', 'string'],
            'email' => ['required_without:phone', 'nullable', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required_without' => 'Merci d\'indiquer votre numéro ou votre e-mail.',
            'email.required_without' => 'Merci d\'indiquer votre e-mail ou votre numéro.',
            'password.required' => 'Merci de saisir votre mot de passe.',
        ];
    }

    /**
     * L'identifiant réellement renseigné par le visiteur (numéro ou e-mail),
     * selon l'onglet actif dans la modale.
     */
    public function identifierColumn(): string
    {
        return $this->filled('phone') ? 'phone' : 'email';
    }

    public function identifierValue(): string
    {
        return $this->filled('phone') ? (string) $this->input('phone') : (string) $this->input('email');
    }
}