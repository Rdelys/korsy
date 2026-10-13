<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
     * Met à jour le profil : nom, e-mail, téléphone + intitulé, établissement,
     * série/matière, ville et présentation (vendeur).
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

    /**
     * Enregistre (ou remplace) la photo de profil.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validateWithBag('avatar', [
            'avatar' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=100,min_height=100'],
        ], [
            'avatar.required' => 'Merci de choisir une photo.',
            'avatar.file' => 'Le fichier envoyé est invalide.',
            'avatar.uploaded' => 'La photo n\'a pas pu être envoyée (2 Mo maximum).',
            'avatar.mimes' => 'Format accepté : JPG, PNG ou WebP.',
            'avatar.max' => 'La photo ne doit pas dépasser 2 Mo.',
            'avatar.dimensions' => 'La photo doit mesurer au moins 100 × 100 pixels.',
        ]);

        $user = $request->user();
        $previous = $user->avatar_path;

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->forceFill(['avatar_path' => $path])->save();

        if ($previous) {
            Storage::disk('public')->delete($previous);
        }

        return redirect()->route('profile.show')
            ->with('status', 'Votre photo de profil a été mise à jour.');
    }

    /**
     * Supprime la photo de profil (retour à l'initiale).
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->forceFill(['avatar_path' => null])->save();
        }

        return redirect()->route('profile.show')
            ->with('status', 'Votre photo de profil a été supprimée.');
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