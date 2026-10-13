<?php

namespace App\Http\Requests;

use App\Support\NormalizesMalagasyPhone;
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
        $id = $this->user()->id;

        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone' => [
                'required',
                'regex:/^\+261(32|33|34|37|38)\d{7}$/',
                Rule::unique('users', 'phone')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Merci d\'indiquer votre nom complet.',
            'email.required' => 'Merci d\'indiquer votre adresse e-mail.',
            'email.email' => 'Cette adresse e-mail ne semble pas valide.',
            'email.unique' => 'Un autre compte utilise déjà cette adresse e-mail.',
            'phone.required' => 'Le numéro Mobile Money est requis.',
            'phone.regex' => 'Numéro invalide. Utilisez un numéro MVola, Orange Money ou Airtel Money malgache.',
            'phone.unique' => 'Ce numéro est déjà associé à un autre compte.',
        ];
    }
}