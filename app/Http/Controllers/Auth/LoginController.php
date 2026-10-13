<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\LoginOtpMail;
use App\Models\LoginOtp;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Étape 1 : vérifie l'identifiant (téléphone ou e-mail) et le mot de passe.
     * Si l'appareil est déjà reconnu, la connexion est immédiate ; sinon, un
     * code OTP est envoyé par e-mail avant de finaliser la connexion.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $throttleKey = 'login:'.$request->identifierValue().'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 6)) {
            return $this->backToLogin($request)->withErrors([
                'credentials' => 'Trop de tentatives. Réessayez dans '.RateLimiter::availableIn($throttleKey).' secondes.',
            ]);
        }

        $user = User::where($request->identifierColumn(), $request->identifierValue())->first();

        if (! $user || ! Hash::check($request->string('password'), $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return $this->backToLogin($request)->withErrors([
                'credentials' => 'Identifiants incorrects. Vérifiez votre numéro/e-mail et votre mot de passe.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $remember = $request->boolean('remember');

        if ($device = $this->currentTrustedDevice($request, $user)) {
            $device->forceFill(['last_used_at' => now(), 'ip_address' => $request->ip()])->save();

            return $this->completeLogin($request, $user, $remember);
        }

        // Navigateur non reconnu : c'est, par définition, une « première connexion sur ce
        // navigateur » pour ce compte — on exige donc un code OTP avant de finaliser.
        return $this->sendOtpAndPrompt($request, $user, $remember);
    }

    /**
     * Étape 2 : vérifie le code OTP saisi et finalise la connexion.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('home')->with([
                'open_modal' => 'auth', 'auth_tab' => 'login',
            ])->withErrors(['code' => 'Votre session a expiré, merci de vous reconnecter.']);
        }

        $request->validate(['code' => ['required', 'digits:6']], [
            'code.required' => 'Merci de saisir le code reçu par e-mail.',
            'code.digits' => 'Le code doit contenir 6 chiffres.',
        ]);

        $otp = $user->loginOtps()->whereNull('used_at')->latest('id')->first();

        if (! $otp || $otp->isExpired()) {
            return $this->backToOtp($request)->withErrors([
                'code' => 'Ce code a expiré. Demandez-en un nouveau ci-dessous.',
            ]);
        }

        if ($otp->attempts >= LoginOtp::MAX_ATTEMPTS) {
            $otp->markUsed();

            return $this->backToOtp($request)->withErrors([
                'code' => 'Trop de tentatives incorrectes. Demandez un nouveau code.',
            ]);
        }

        if (! $otp->matches($request->string('code'))) {
            $otp->increment('attempts');

            return $this->backToOtp($request)->withErrors([
                'code' => 'Code incorrect. Il vous reste '.(LoginOtp::MAX_ATTEMPTS - $otp->attempts).' tentative(s).',
            ]);
        }

        $otp->markUsed();

        $remember = (bool) session('auth.otp.remember', false);
        $isRegistration = session('auth.otp.context') === 'register';
        $this->forgetPendingOtp($request);

        // Le code reçu par e-mail prouve que l'adresse est valide.
        if (! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $token = TrustedDevice::trust($user, $request->ip(), TrustedDevice::guessLabel($request->userAgent()));
        Cookie::queue(Cookie::make(
            name: TrustedDevice::COOKIE_NAME,
            value: $token,
            minutes: TrustedDevice::TRUST_DAYS * 24 * 60,
            httpOnly: true,
            secure: $request->isSecure(),
            sameSite: 'lax',
        ));

        return $this->completeLogin(
            $request,
            $user,
            $remember,
            $isRegistration
                ? 'Compte créé et vérifié. Bienvenue sur Tafely Kôrsy, '.$user->name.' !'
                : 'Bienvenue ! Votre navigateur est maintenant reconnu.'
        );
    }

    /**
     * Renvoie un nouveau code OTP (limité à un envoi par minute).
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('home')->with(['open_modal' => 'auth', 'auth_tab' => 'login']);
        }

        $throttleKey = 'otp-resend:'.$user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            return $this->backToOtp($request)->withErrors([
                'code' => 'Merci de patienter '.RateLimiter::availableIn($throttleKey).' secondes avant de redemander un code.',
            ]);
        }

        RateLimiter::hit($throttleKey, 60);

        [$otp, $code] = LoginOtp::generateFor($user, $request->ip());

        try {
            Mail::to($user->email)->send(new LoginOtpMail(
                user: $user,
                code: $code,
                deviceLabel: TrustedDevice::guessLabel($request->userAgent()),
            ));
        } catch (\Throwable $e) {
            Log::error('Envoi du code OTP (renvoi) impossible : '.$e->getMessage());

            return $this->backToOtp($request)->withErrors([
                'code' => "L'envoi du code a échoué. Réessayez dans un instant.",
            ]);
        }

        return $this->backToOtp($request)->with('status', 'Un nouveau code vient d\'être envoyé à '.RegisterController::maskEmail($user->email).'.');
    }

    /**
     * Annule la connexion en attente d'OTP (ex. « utiliser un autre compte »).
     */
    public function cancelOtp(Request $request): RedirectResponse
    {
        $this->forgetPendingOtp($request);

        return redirect()->route('home')->with(['open_modal' => 'auth', 'auth_tab' => 'login']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Le cookie d'appareil de confiance n'est volontairement PAS supprimé : une
        // déconnexion normale ne doit pas redemander un OTP à la prochaine connexion
        // sur ce même navigateur (seule la toute première connexion l'exige).
        return redirect()->route('home')->with('status', 'Vous avez été déconnecté.');
    }

    /* -------------------------------------------------------------------- */

    private function sendOtpAndPrompt(LoginRequest $request, User $user, bool $remember): RedirectResponse
    {
        [$otp, $code] = LoginOtp::generateFor($user, $request->ip());

        try {
            Mail::to($user->email)->send(new LoginOtpMail(
                user: $user,
                code: $code,
                deviceLabel: TrustedDevice::guessLabel($request->userAgent()),
            ));
        } catch (\Throwable $e) {
            Log::error('Envoi du code OTP (connexion) impossible : '.$e->getMessage());

            return $this->backToLogin($request)->withErrors([
                'credentials' => "Identifiants valides, mais l'envoi du code de vérification a échoué. Réessayez dans un instant.",
            ]);
        }

        session([
            'auth.otp.user_id' => $user->id,
            'auth.otp.remember' => $remember,
            'auth.otp.context' => 'login',
        ]);

        return redirect()->back()->with([
            'open_modal' => 'auth',
            'auth_step' => 'otp',
            'auth_role' => $user->role,
            'otp_email_masked' => RegisterController::maskEmail($user->email),
        ]);
    }

    private function completeLogin(Request $request, User $user, bool $remember, ?string $status = null): RedirectResponse
    {
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('status', $status ?? 'Connexion réussie. Bienvenue, '.$user->name.' !');
    }

    private function currentTrustedDevice(Request $request, User $user): ?TrustedDevice
    {
        $token = $request->cookie(TrustedDevice::COOKIE_NAME);

        return $token ? TrustedDevice::findByToken($user, $token) : null;
    }

    private function pendingUser(Request $request): ?User
    {
        $id = $request->session()->get('auth.otp.user_id');

        return $id ? User::find($id) : null;
    }

    private function forgetPendingOtp(Request $request): void
    {
        $request->session()->forget(['auth.otp.user_id', 'auth.otp.remember', 'auth.otp.context']);
    }

    private function backToLogin(Request $request): RedirectResponse
    {
        return redirect()->back()
            ->withInput($request->except('password'))
            ->with(['open_modal' => 'auth', 'auth_tab' => 'login']);
    }

    private function backToOtp(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        return redirect()->back()->with(array_filter([
            'open_modal' => 'auth',
            'auth_step' => 'otp',
            'otp_email_masked' => $user ? RegisterController::maskEmail($user->email) : null,
        ]));
    }
}