<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** Statuts de compte possibles (colonne `role`). */
    public const ROLE_CLIENT = 'client';

    public const ROLE_VENDEUR = 'vendeur';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Codes de vérification (OTP) envoyés par e-mail lors des connexions.
     *
     * @return HasMany<LoginOtp>
     */
    public function loginOtps(): HasMany
    {
        return $this->hasMany(LoginOtp::class);
    }

    /**
     * Navigateurs / appareils déjà reconnus, qui n'ont donc plus besoin d'OTP.
     *
     * @return HasMany<TrustedDevice>
     */
    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }

    /**
     * L'utilisateur est-il inscrit comme acheteur / étudiant ?
     */
    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    /**
     * L'utilisateur est-il inscrit comme vendeur / enseignant ?
     */
    public function isVendeur(): bool
    {
        return $this->role === self::ROLE_VENDEUR;
    }

    /**
     * Libellé du profil, tel qu'affiché dans l'interface (menu, badges…).
     */
    public function roleLabel(): string
    {
        return $this->isVendeur() ? 'Vendeur Certifié' : 'Apprenant';
    }

    /**
     * Initiale utilisée dans l'avatar rond du menu quand l'utilisateur n'a pas de photo.
     */
    public function initial(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}