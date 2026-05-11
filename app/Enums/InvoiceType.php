<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Consultation = 'consultation';
    case Optical      = 'optical';
    case Pharmacy     = 'pharmacy';
    case Exam         = 'exam';
    case Global       = 'global';

    public function label(): string
    {
        return match($this) {
            self::Consultation => 'Consultation',
            self::Optical      => 'Optique',
            self::Pharmacy     => 'Pharmacie',
            self::Exam         => 'Examen',
            self::Global       => 'Global',
        };
    }
}
