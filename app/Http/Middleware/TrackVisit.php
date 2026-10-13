<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|bing|preview|monitor|facebookexternalhit|headless|curl|wget|python|httpx|go-http|java\//i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if ($this->shouldTrack($request, $response)) {
                $request->session()->put('visit_tracked', true);

                [$source, $host] = Visit::detectSource($request);

                Visit::create([
                    'source' => $source,
                    'referrer_host' => $host,
                    'landing_path' => Str::limit('/'.ltrim($request->path(), '/'), 255, ''),
                    'user_id' => $request->user()?->id,
                    'ip' => $request->ip(),
                    'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
                ]);
            }
        } catch (\Throwable $e) {
            report($e); // le suivi ne doit jamais casser une page
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $request->ajax() || ! $request->hasSession()) {
            return false;
        }

        if ($request->is('admin', 'admin/*') || $request->session()->has('visit_tracked')) {
            return false;
        }

        if (! $response->isSuccessful()
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        return ! preg_match(self::BOT_PATTERN, (string) $request->userAgent());
    }
}