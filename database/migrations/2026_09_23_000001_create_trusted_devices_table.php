<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Jeton stocké dans un cookie chiffré côté navigateur (voir LoginController).
            // Seul son hachage est conservé en base, comme pour un mot de passe.
            $table->string('token_hash')->unique();

            $table->string('label')->nullable();       // ex. « Chrome sur Windows »
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('last_used_at')->nullable();

            // Au-delà de cette date, l'appareil n'est plus considéré comme reconnu
            // et un nouveau code OTP sera redemandé (voir User::ROLE_* / TrustedDevice::TRUST_DAYS).
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['user_id', 'token_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trusted_devices');
    }
};