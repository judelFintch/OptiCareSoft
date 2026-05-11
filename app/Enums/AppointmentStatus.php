<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Scheduled     = 'scheduled';
    case Confirmed     = 'confirmed';
    case Waiting       = 'waiting';
    case InConsultation = 'in_consultation';
    case Completed     = 'completed';
    case Cancelled     = 'cancelled';
    case Missed        = 'missed';

    public function label(): string
    {
        return match($this) {
            self::Scheduled      => 'Planifié',
            self::Confirmed      => 'Confirmé',
            self::Waiting        => 'En attente',
            self::InConsultation => 'En consultation',
            self::Completed      => 'Terminé',
            self::Cancelled      => 'Annulé',
            self::Missed         => 'Manqué',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Scheduled      => 'blue',
            self::Confirmed      => 'indigo',
            self::Waiting        => 'yellow',
            self::InConsultation => 'purple',
            self::Completed      => 'green',
            self::Cancelled      => 'red',
            self::Missed         => 'gray',
        };
    }
}
