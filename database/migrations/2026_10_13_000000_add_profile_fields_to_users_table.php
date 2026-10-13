<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('headline', 150)->nullable()->after('phone');   // intitulé affiché sous le nom
            $table->string('school', 255)->nullable()->after('headline');  // établissement
            $table->string('level', 100)->nullable()->after('school');     // série (apprenant) ou matière (vendeur)
            $table->string('city', 100)->nullable()->after('level');
            $table->text('bio')->nullable()->after('city');                // vendeur uniquement

            // Utilisée par ProfileController::update() ; créée seulement si elle n'existe pas déjà.
            if (! Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['headline', 'school', 'level', 'city', 'bio']);
            // phone_verified_at n'est volontairement pas supprimée (elle peut préexister).
        });
    }
};