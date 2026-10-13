<!DOCTYPE html>
<html lang="fr">
<body style="margin:0;padding:24px;background:#f6f8fe;font-family:Arial,Helvetica,sans-serif;color:#0f1b33;">
    <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:12px;padding:32px;border:1px solid #c2cbe0;">
        <h1 style="font-size:18px;margin:0 0 8px;color:#0b3b8f;">Connexion à l'administration</h1>
        <p style="font-size:14px;line-height:22px;margin:0 0 20px;">
            Utilisez ce code pour terminer votre connexion. Il est valable <strong>10 minutes</strong>.
        </p>
        <p style="font-size:34px;letter-spacing:10px;font-weight:700;text-align:center;margin:0 0 20px;color:#0b3b8f;">{{ $code }}</p>
        <p style="font-size:12px;line-height:18px;color:#6b7590;margin:0;">
            Demande effectuée depuis l'adresse IP {{ $ip ?? 'inconnue' }}.<br>
            Si vous n'êtes pas à l'origine de cette demande, ne communiquez ce code à personne et changez votre mot de passe.
        </p>
    </div>
</body>
</html>