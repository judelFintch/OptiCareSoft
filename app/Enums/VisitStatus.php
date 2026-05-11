<?php

namespace App\Enums;

enum VisitStatus: string
{
    case Open        = 'open';
    case InProgress  = 'in_progress';
    case Pending     = 'pending';
    case Closed      = 'closed';
    case Cancelled   = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Open       => 'Ouvert',
            self::InProgress => 'En cours',
            self::Pending    => 'En attente paiement',
            self::Closed     => 'Clôturé',
            self::Cancelled  => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Open       => 'blue',
            self::InProgress => 'purple',
            self::Pending    => 'yellow',
            self::Closed     => 'green',
            self::Cancelled  => 'red',
        };
    }
}
