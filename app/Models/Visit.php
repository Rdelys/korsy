<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Visit extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['source', 'referrer_host', 'landing_path', 'user_id', 'ip', 'user_agent'];

    /**
     * Déduit la source de la visite : paramètre utm_source, sinon domaine du référent, sinon « Direct ».
     *
     * @return array{0: string, 1: ?string} [source, domaine du référent]
     */
    public static function detectSource(Request $request): array
    {
        $normalize = fn (?string $h) => $h ? Str::lower(preg_replace('/^www\./', '', $h)) : null;

        $host = $normalize(parse_url((string) $request->headers->get('referer'), PHP_URL_HOST) ?: null);

        // Navigation interne : pas une source externe.
        if ($host && $host === $normalize($request->getHost())) {
            $host = null;
        }

        $utm = trim((string) $request->query('utm_source', ''));
        if ($utm !== '') {
            return [Str::limit(Str::title($utm), 60, ''), $host];
        }

        if (! $host) {
            return ['Direct', null];
        }

        $patterns = [
            '/(^|\.)google\.[a-z.]+$/' => 'Google',
            '/(^|\.)bing\.com$/' => 'Bing',
            '/(^|\.)yahoo\.com$/' => 'Yahoo',
            '/(^|\.)duckduckgo\.com$/' => 'DuckDuckGo',
            '/(^|\.)(facebook|fb)\.(com|me)$/' => 'Facebook',
            '/(^|\.)instagram\.com$/' => 'Instagram',
            '/(^|\.)tiktok\.com$/' => 'TikTok',
            '/(^|\.)(youtube\.com|youtu\.be)$/' => 'YouTube',
            '/(^|\.)(whatsapp\.com|wa\.me)$/' => 'WhatsApp',
            '/(^|\.)(twitter\.com|x\.com|t\.co)$/' => 'X (Twitter)',
            '/(^|\.)linkedin\.com$/' => 'LinkedIn',
            '/(^|\.)(telegram\.org|t\.me)$/' => 'Telegram',
        ];

        foreach ($patterns as $regex => $label) {
            if (preg_match($regex, $host)) {
                return [$label, $host];
            }
        }

        return [Str::limit($host, 60, ''), $host];
    }
}