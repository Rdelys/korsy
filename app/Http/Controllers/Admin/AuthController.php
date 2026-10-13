<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use App\Models\Admin;
use App\Models\AdminLoginAttempt;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const OTP_TTL_MINUTES = 10;

    private const OTP_MAX_ATTEMPTS = 5;

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /** Étape 1 : e-mail + mot de passe. Seule l'adresse autorisée passe ; le reste est journalisé. */
    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Merci de saisir votre adresse e-mail.',
            'password.required' => 'Merci de saisir votre mot de passe.',
        ]);

        $email = Str::lower(trim($data['email']));
        $allowed = Str::lower((string) config('admin.email'));

        // Toute autre adresse est refusée (et signalée dans le menu Sécurité).
        if (! hash_equals($allowed, $email)) {
            $this->record($request, $email, AdminLoginAttempt::UNAUTHORIZED_EMAIL);

            return $this->deny();
        }

        $admin = Admin::where('email', $allowed)->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            $this->record($request, $email, AdminLoginAttempt::BAD_PASSWORD);

            return $this->deny();
        }

        try {
            $this->sendOtp($admin, $request);
        } catch (\Throwable $e) {
            Log::error('Envoi du code OTP admin impossible : '.$e->getMessage());

            return back()->withErrors(['email' => "Impossible d'envoyer le code de vérification. Réessayez dans un instant."]);
        }

        $request->session()->put('admin.otp.admin_id', $admin->id);

        return redirect()->route('admin.otp');
    }

    public function showOtp(Request $request): View|RedirectResponse
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.otp', ['maskedEmail' => $this->maskEmail($admin->email)]);
    }

    /** Étape 2 : vérification du code reçu par e-mail. */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate(['code' => ['required', 'digits:6']], [
            'code.required' => 'Merci de saisir le code reçu par e-mail.',
            'code.digits' => 'Le code comporte 6 chiffres.',
        ]);

        $key = $this->otpKey($admin->id);
        $entry = Cache::get($key);

        if (! $entry || $entry['expires_at'] < now()->timestamp) {
            Cache::forget($key);

            return back()->withErrors(['code' => 'Ce code a expiré. Demandez-en un nouveau.']);
        }

        if ($entry['attempts'] >= self::OTP_MAX_ATTEMPTS) {
            Cache::forget($key);
            $request->session()->forget('admin.otp.admin_id');
            $this->record($request, $admin->email, AdminLoginAttempt::BAD_OTP);

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Trop de codes incorrects. Veuillez recommencer.']);
        }

        if (! Hash::check($data['code'], $entry['hash'])) {
            $entry['attempts']++;
            Cache::put($key, $entry, Carbon::createFromTimestamp($entry['expires_at']));
            $this->record($request, $admin->email, AdminLoginAttempt::BAD_OTP);

            return back()->withErrors(['code' => 'Code incorrect.']);
        }

        Cache::forget($key);
        $request->session()->forget('admin.otp.admin_id');

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        $admin->forceFill(['last_login_at' => now(), 'last_login_ip' => $request->ip()])->save();
        $this->record($request, $admin->email, AdminLoginAttempt::SUCCESS);

        return redirect()->route('admin.dashboard');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        try {
            $this->sendOtp($admin, $request);
        } catch (\Throwable $e) {
            Log::error('Renvoi du code OTP admin impossible : '.$e->getMessage());

            return back()->withErrors(['code' => "Impossible d'envoyer le code. Réessayez dans un instant."]);
        }

        return back()->with('status', 'Un nouveau code vient de vous être envoyé.');
    }

    public function cancelOtp(Request $request): RedirectResponse
    {
        $request->session()->forget('admin.otp.admin_id');

        return redirect()->route('admin.login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerate();

        return redirect()->route('admin.login')->with('status', 'Vous avez été déconnecté.');
    }

    /* ------------------------------------------------------------------ */

    private function deny(): RedirectResponse
    {
        // Même message dans tous les cas : on ne révèle pas quelle adresse est autorisée.
        return back()
            ->withErrors(['email' => 'Identifiants invalides ou accès non autorisé.'])
            ->onlyInput('email');
    }

    private function record(Request $request, string $email, string $reason): void
    {
        try {
            AdminLoginAttempt::create([
                'email' => Str::limit($email, 255, ''),
                'ip' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
                'reason' => $reason,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function pendingAdmin(Request $request): ?Admin
    {
        $id = $request->session()->get('admin.otp.admin_id');

        return $id ? Admin::find($id) : null;
    }

    private function sendOtp(Admin $admin, Request $request): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(self::OTP_TTL_MINUTES);

        Cache::put($this->otpKey($admin->id), [
            'hash' => Hash::make($code),
            'attempts' => 0,
            'expires_at' => $expiresAt->timestamp,
        ], $expiresAt);

        Mail::to($admin->email)->send(new AdminOtpMail($code, $request->ip(), $request->userAgent()));
    }

    private function otpKey(int $adminId): string
    {
        return 'admin_otp:'.$adminId;
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        return mb_substr($local, 0, 1).str_repeat('*', max(mb_strlen($local) - 1, 3)).'@'.$domain;
    }
}