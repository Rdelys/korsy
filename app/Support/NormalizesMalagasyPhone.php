<?php

namespace App\Support;

trait NormalizesMalagasyPhone
{
    /**
     * Normalise un numéro Mobile Money malgache saisi sans l'indicatif (le +261 est
     * affiché séparément dans le formulaire) vers le format international complet
     * +261XXXXXXXXX, utilisé comme identifiant unique en base de données.
     *
     * Accepte aussi bien « 34 00 000 00 », « 034 00 000 00 » que « +261 34 00 000 00 ».
     */
    protected function normalizePhone(?string $raw): ?string
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        // Déjà au format international (261XXXXXXXXX = 12 chiffres).
        if (str_starts_with($digits, '261') && strlen($digits) === 12) {
            return '+'.$digits;
        }

        // Format national avec le 0 initial (0XX XX XXX XX = 10 chiffres).
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return '+261'.substr($digits, 1);
        }

        // Format saisi tel qu'affiché dans le champ, sans le 0 ni l'indicatif (9 chiffres).
        if (strlen($digits) === 9) {
            return '+261'.$digits;
        }

        // Format inattendu : on renvoie tel quel (préfixé) pour que la règle de
        // validation `regex` du FormRequest le rejette proprement plutôt que de
        // silencieusement enregistrer une valeur incorrecte.
        return '+'.ltrim($digits, '+');
    }
}