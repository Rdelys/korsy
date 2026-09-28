<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pages publiques
|--------------------------------------------------------------------------
*/

Route::view('/', 'home')->name('home');
Route::view('/catalogue', 'catalogue')->name('catalogue');

/*
|--------------------------------------------------------------------------
| Authentification (visiteurs uniquement)
|--------------------------------------------------------------------------
| La modale unique (voir resources/views/partials/auth-modals.blade.php)
| soumet ses formulaires à ces routes. En cas d'erreur ou d'étape OTP,
| le contrôleur redirige vers la page précédente avec les indications
| nécessaires (open_modal, auth_tab, auth_step…) pour la rouvrir au bon
| endroit — voir le script de resources/views/layouts/app.blade.php.
*/

Route::middleware('guest')->group(function () {
    Route::post('/inscription', [RegisterController::class, 'store'])
        ->name('register')
        ->middleware('throttle:10,1');

    Route::post('/connexion', [LoginController::class, 'store'])
        ->name('login')
        ->middleware('throttle:10,1');

    Route::post('/connexion/otp', [LoginController::class, 'verifyOtp'])
        ->name('login.otp.verify')
        ->middleware('throttle:20,1');

    Route::post('/connexion/otp/renvoyer', [LoginController::class, 'resendOtp'])
        ->name('login.otp.resend')
        ->middleware('throttle:10,1');

    Route::get('/connexion/otp/annuler', [LoginController::class, 'cancelOtp'])
        ->name('login.otp.cancel');
});

/*
|--------------------------------------------------------------------------
| Espace connecté
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
});