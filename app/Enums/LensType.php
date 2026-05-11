<?php

namespace App\Enums;

enum LensType: string
{
    case Unifocal     = 'unifocal';
    case Bifocal      = 'bifocal';
    case Progressive  = 'progressive';
    case Degressive   = 'degressive';
    case ContactLens  = 'contact_lens';

    public function label(): string
    {
        return match($this) {
            self::Unifocal    => 'Unifocal',
            self::Bifocal     => 'Bifocal',
            self::Progressive => 'Progressif',
            self::Degressive  => 'Dégressif',
            self::ContactLens => 'Lentille de contact',
        };
    }
}
