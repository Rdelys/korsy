<?php

namespace App\Providers;

use App\Models\LoginEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Journalise chaque connexion d'un utilisateur du site (hors guard admin) pour les statistiques.
        Event::listen(Login::class, function (Login $event) {
            if ($event->guard !== 'web') {
                return;
            }

            try {
                LoginEvent::create([
                    'user_id' => $event->user->getAuthIdentifier(),
                    'ip' => request()->ip(),
                    'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        });
    }
}