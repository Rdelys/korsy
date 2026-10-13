<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamps();
        });

        // Journal des tentatives de connexion à l'admin (réussies ou non).
        Schema::create('admin_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('reason', 30)->index(); // unauthorized_email | bad_password | bad_otp | success
            $table->timestamp('seen_at')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        // Une ligne par visite (= début de session d'un visiteur).
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('source', 60)->index();
            $table->string('referrer_host')->nullable();
            $table->string('landing_path')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        // Une ligne par connexion réussie d'un utilisateur du site.
        Schema::create('login_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('profile_verified_at')->nullable();

            if (! Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_verified_at');
        });

        Schema::dropIfExists('login_events');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('admin_login_attempts');
        Schema::dropIfExists('admins');
    }
};