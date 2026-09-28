<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

#[Fillable(['user_id', 'code_hash', 'expires_at', 'used_at', 'ip_address', 'attempts'])]
#[Hidden(['code_hash'])]
class LoginOtp extends Model
{
    /** Durée de validité d'un code, en minutes. */
    public const TTL_MINUTES = 10;

    /** Nombre d'essais incorrects autorisés avant qu'un code soit invalidé. */
    public const MAX_ATTEMPTS = 5;

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, LoginOtp>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Génère un nouveau code à 6 chiffres pour cet utilisateur et retourne
     * le modèle ainsi que le code en clair (à envoyer par e-mail, jamais stocké).
     */
    public static function generateFor(User $user, ?string $ip = null): array
    {
        $code = (string) random_int(100000, 999999);

        $otp = $user->loginOtps()->create([
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
            'ip_address' => $ip,
        ]);

        return [$otp, $code];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function matches(string $code): bool
    {
        return Hash::check($code, $this->code_hash);
    }

    public function markUsed(): void
    {
        $this->forceFill(['used_at' => now()])->save();
    }
}