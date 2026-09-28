<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Votre code de vérification</title>
</head>
<body style="margin:0; padding:0; background-color:#f6f8fe; font-family:Arial, Helvetica, sans-serif; color:#0f1b33;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f6f8fe; padding:32px 16px;">
<tr>
<td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px; background-color:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #d9e3f7;">

    {{-- Liseré bleu / rouge / bleu --}}
    <tr>
        <td style="height:6px; line-height:6px; font-size:0; background:linear-gradient(90deg, #1653c9 0%, #c8102e 50%, #1653c9 100%); background-color:#1653c9;">&nbsp;</td>
    </tr>

    {{-- En-tête --}}
    <tr>
        <td align="center" style="padding:28px 32px 8px;">
            <span style="display:inline-block; width:40px; height:40px; line-height:40px; border-radius:8px; background-color:#1653c9; color:#ffffff; font-size:18px; font-weight:bold; text-align:center;">TK</span>
            <p style="margin:12px 0 0; font-size:18px; font-weight:bold; color:#0b3b8f;">Tafely Kôrsy</p>
        </td>
    </tr>

    {{-- Corps --}}
    <tr>
        <td style="padding:8px 32px 24px;">
            <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">Bonjour {{ $name }},</p>
            <p style="margin:0 0 20px; font-size:15px; line-height:1.6;">
                @if ($deviceLabel)
                    Une connexion a été demandée depuis un nouvel appareil (<strong>{{ $deviceLabel }}</strong>) non reconnu par Tafely Kôrsy. Pour confirmer qu'il s'agit bien de vous, saisissez le code suivant&nbsp;:
                @else
                    Une connexion a été demandée depuis un appareil non reconnu par Tafely Kôrsy. Pour confirmer qu'il s'agit bien de vous, saisissez le code suivant&nbsp;:
                @endif
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 20px;">
                <tr>
                    <td align="center" style="background-color:#eef3fd; border:1px solid #d9e3f7; border-radius:10px; padding:18px;">
                        <span style="font-size:32px; font-weight:bold; letter-spacing:10px; color:#0b3b8f;">{{ $code }}</span>
                    </td>
                </tr>
            </table>

            <p style="margin:0 0 8px; font-size:13px; line-height:1.6; color:#40495e;">
                Ce code est valable {{ $ttlMinutes }} minutes et ne peut être utilisé qu'une seule fois.
            </p>
            <p style="margin:0; font-size:13px; line-height:1.6; color:#40495e;">
                Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail : votre compte reste protégé et personne ne pourra s'y connecter sans ce code.
            </p>
        </td>
    </tr>

    {{-- Pied --}}
    <tr>
        <td style="padding:16px 32px 28px; border-top:1px solid #eef3fd;">
            <p style="margin:0; font-size:12px; line-height:1.6; color:#6b7590;">
                © {{ date('Y') }} Tafely Kôrsy — L'excellence pédagogique certifiée &amp; commerce académique.
            </p>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
</body>
</html>