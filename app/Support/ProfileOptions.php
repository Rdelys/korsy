<?php

namespace App\Support;

class ProfileOptions
{
    public const SUBJECTS = [
        'Mathématiques',
        'Physique-Chimie',
        'SVT',
        'Français & Littérature',
        'Philosophie',
        'Histoire-Géographie',
        'Anglais',
        'Informatique',
    ];

    public const LEVELS = [
        'Série Scientifique C (Maths & Physiques)',
        'Série Scientifique D (SVT & Chimie)',
        'Série Littéraire A1 / A2',
        'Série Technique / Tertiaire',
        'Étudiant Supérieur / Faculté des Sciences',
    ];

    /** Options du champ « level » selon le profil. */
    public static function levelsFor(bool $isVendeur): array
    {
        return $isVendeur ? self::SUBJECTS : self::LEVELS;
    }
}