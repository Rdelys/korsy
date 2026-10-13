<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginAttempt extends Model
{
    public const UPDATED_AT = null;

    public const UNAUTHORIZED_EMAIL = 'unauthorized_email';
    public const BAD_PASSWORD = 'bad_password';
    public const BAD_OTP = 'bad_otp';
    public const SUCCESS = 'success';

    protected $fillable = ['email', 'ip', 'user_agent', 'reason', 'seen_at'];

    protected function casts(): array
    {
        return ['seen_at' => 'datetime'];
    }

    /** Tentatives suspectes pas encore consultées (pastille du menu Sécurité). */
    public static function unseenCount(): int
    {
        return static::whereNull('seen_at')->where('reason', '!=', self::SUCCESS)->count();
    }

    public function label(): string
    {
        return match ($this->reason) {
            self::UNAUTHORIZED_EMAIL => 'E-mail non autorisé',
            self::BAD_PASSWORD => 'Mot de passe incorrect',
            self::BAD_OTP => 'Code OTP incorrect',
            self::SUCCESS => 'Connexion réussie',
            default => $this->reason,
        };
    }

    public function badgeClasses(): string
    {
        return match ($this->reason) {
            self::UNAUTHORIZED_EMAIL => 'bg-red-100 text-red-700',
            self::BAD_PASSWORD, self::BAD_OTP => 'bg-amber-100 text-amber-700',
            self::SUCCESS => 'bg-emerald-100 text-emerald-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }
}