<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'token_hash', 'label', 'ip_address', 'last_used_at', 'expires_at'])]
#[Hidden(['token_hash'])]
class TrustedDevice extends Model
{
    /** Durée de confiance d'un appareil, en jours, avant de redemander un code OTP. */
    public const TRUST_DAYS = 365;

    /** Nom du cookie stocké chez le visiteur (voir LoginController). */
    public const COOKIE_NAME = 'tafely_device';

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, TrustedDevice>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }

    /**
     * Enregistre l'appareil courant comme reconnu pour cet utilisateur et
     * retourne le jeton en clair, à placer dans le cookie du navigateur.
     */
    public static function trust(User $user, ?string $ip = null, ?string $label = null): string
    {
        $token = Str::random(64);

        $user->trustedDevices()->create([
            'token_hash' => hash('sha256', $token),
            'label' => $label,
            'ip_address' => $ip,
            'last_used_at' => now(),
            'expires_at' => now()->addDays(self::TRUST_DAYS),
        ]);

        return $token;
    }

    /**
     * Retrouve l'appareil de confiance correspondant à un jeton de cookie, s'il existe encore.
     */
    public static function findByToken(User $user, string $token): ?self
    {
        return $user->trustedDevices()
            ->where('token_hash', hash('sha256', $token))
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Devine un libellé lisible (« Chrome sur Windows ») à partir du user-agent,
     * juste pour affichage — jamais utilisé pour la sécurité elle-même.
     */
    public static function guessLabel(?string $userAgent): ?string
    {
        if (! $userAgent) {
            return null;
        }

        $browser = match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') && ! str_contains($userAgent, 'Chrome/') => 'Safari',
            default => 'Navigateur',
        };

        $system = match (true) {
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Mac OS') => 'macOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => null,
        };

        return $system ? "{$browser} sur {$system}" : $browser;
    }
}