<?php

namespace App\Enums;

enum ExamType: string
{
    case VisualAcuity     = 'visual_acuity';
    case Refraction       = 'refraction';
    case Tonometry        = 'tonometry';
    case Fundus           = 'fundus';
    case Oct              = 'oct';
    case VisualField      = 'visual_field';
    case Biometry         = 'biometry';
    case Ultrasound       = 'ultrasound';
    case Topography       = 'topography';
    case Other            = 'other';

    public function label(): string
    {
        return match($this) {
            self::VisualAcuity => 'Acuité visuelle',
            self::Refraction   => 'Réfraction',
            self::Tonometry    => 'Tonométrie',
            self::Fundus       => "Fond d'œil",
            self::Oct          => 'OCT',
            self::VisualField  => 'Champ visuel',
            self::Biometry     => 'Biométrie',
            self::Ultrasound   => 'Échographie oculaire',
            self::Topography   => 'Topographie cornéenne',
            self::Other        => 'Autre',
        };
    }
}
