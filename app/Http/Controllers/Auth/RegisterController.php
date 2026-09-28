<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\LoginOtpMail;
use App\Models\LoginOtp;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'role' => $data['role'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => $data['password'], // haché automatiquement (cast 'hashed').
        ]);

        // Un compte tout juste créé équivaut, par définition, à une première connexion
        // sur ce navigateur : on demande donc immédiatement un code OTP par e-mail,
        // plutôt que de connecter directement l'utilisateur.
        [$otp, $code] = LoginOtp::generateFor($user, $request->ip());

        try {
            Mail::to($user->email)->send(new LoginOtpMail(
                user: $user,
                code: $code,
                deviceLabel: TrustedDevice::guessLabel($request->userAgent()),
            ));
        } catch (\Throwable $e) {
            Log::error('Envoi du code OTP (inscription) impossible : '.$e->getMessage());

            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['otp' => "Votre compte a bien été créé, mais l'envoi du code de vérification a échoué. Réessayez de vous connecter dans un instant."])
                ->with(['open_modal' => 'auth', 'auth_tab' => 'login', 'auth_role' => $data['role']]);
        }

        session([
            'auth.otp.user_id' => $user->id,
            'auth.otp.remember' => false,
        ]);

        return redirect()->back()->with([
            'open_modal' => 'auth',
            'auth_step' => 'otp',
            'auth_role' => $data['role'],
            'otp_email_masked' => self::maskEmail($user->email),
        ]);
    }

    /**
     * Masque une adresse e-mail pour l'affichage (ex. « h***@exemple.mg »).
     */
    public static function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        $visible = mb_substr($local, 0, 1);

        return $visible.str_repeat('*', max(mb_strlen($local) - 1, 3)).'@'.$domain;
    }
}