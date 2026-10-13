<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        // Double contrôle : session admin valide ET e-mail strictement égal à l'adresse autorisée.
        if (! $admin || ! hash_equals(Str::lower((string) config('admin.email')), Str::lower($admin->email))) {
            if ($admin) {
                Auth::guard('admin')->logout();
            }

            return redirect()->route('admin.login');
        }

        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}