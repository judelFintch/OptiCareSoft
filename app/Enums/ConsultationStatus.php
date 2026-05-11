<?php

namespace App\Enums;

enum ConsultationStatus: string
{
    case Draft     = 'draft';
    case Completed = 'completed';
    case Signed    = 'signed';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Brouillon',
            self::Completed => 'Terminée',
            self::Signed    => 'Signée',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft     => 'yellow',
            self::Completed => 'blue',
            self::Signed    => 'green',
        };
    }
}
