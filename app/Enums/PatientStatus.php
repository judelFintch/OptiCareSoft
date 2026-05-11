<?php

namespace App\Enums;

enum PatientStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Deceased = 'deceased';

    public function label(): string
    {
        return match($this) {
            self::Active   => 'Actif',
            self::Inactive => 'Inactif',
            self::Deceased => 'Décédé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active   => 'green',
            self::Inactive => 'gray',
            self::Deceased => 'red',
        };
    }
}
