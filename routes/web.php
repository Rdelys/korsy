<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])
        ->name('profile.password')
        ->middleware('throttle:6,1');

    // Photo de profil
    Route::post('/profil/photo', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar')
        ->middleware('throttle:10,1');
    Route::delete('/profil/photo', [ProfileController::class, 'destroyAvatar'])
        ->name('profile.avatar.destroy');
});

/*
|--------------------------------------------------------------------------
| Administration (guard « admin », e-mail unique + OTP)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Connexion (publique, mais limitée et journalisée)
    Route::get('/connexion', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AdminAuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.submit');

    Route::get('/verification', [AdminAuthController::class, 'showOtp'])->name('otp');
    Route::post('/verification', [AdminAuthController::class, 'verifyOtp'])
        ->middleware('throttle:10,1')
        ->name('otp.verify');
    Route::post('/verification/renvoyer', [AdminAuthController::class, 'resendOtp'])
        ->middleware('throttle:3,1')
        ->name('otp.resend');
    Route::get('/verification/annuler', [AdminAuthController::class, 'cancelOtp'])->name('otp.cancel');

    // Espace protégé
    Route::middleware('admin')->group(function () {
        Route::post('/deconnexion', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/utilisateurs/{user}/profil', [AdminUserController::class, 'toggleProfile'])->name('users.profile');
        Route::patch('/utilisateurs/{user}/telephone', [AdminUserController::class, 'togglePhone'])->name('users.phone');
        Route::delete('/utilisateurs/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::view('/paiements', 'admin.payments')->name('payments');
        Route::view('/historique', 'admin.history')->name('history');

        Route::get('/securite', [AdminSecurityController::class, 'index'])->name('security');
    });
});