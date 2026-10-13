<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile.show', [
            'user' => $user,
            'devices' => $user->trustedDevices()->latest('last_used_at')->get(),
        ]);
    }

    /**
     * Met à jour les seules informations réellement stockées : nom, e-mail, téléphone.
     * (Établissement, série, bio… sont encore statiques dans la vue.)
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Une nouvelle adresse e-mail n'est plus considérée comme vérifiée.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        // Idem pour un nouveau numéro Mobile Money.
        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('status', 'Votre profil a été mis à jour.')
            ->with('profile_tab', 'info');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8), 'confirmed'],
        ], [
            'current_password.required' => 'Merci de saisir votre mot de passe actuel.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Merci de choisir un nouveau mot de passe.',
            'password.confirmed' => 'La confirmation ne correspond pas au nouveau mot de passe.',
        ]);

        $request->user()->update(['password' => $request->input('password')]); // haché par le cast.

        return redirect()->route('profile.show')
            ->with('status', 'Votre mot de passe a été modifié.')
            ->with('profile_tab', 'security');
    }
}