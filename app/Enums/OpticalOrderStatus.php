<?php

namespace App\Enums;

enum OpticalOrderStatus: string
{
    case Pending      = 'pending';
    case Ordered      = 'ordered';
    case InProduction = 'in_production';
    case Ready        = 'ready';
    case Delivered    = 'delivered';
    case Cancelled    = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending      => 'En attente',
            self::Ordered      => 'Commandé',
            self::InProduction => 'En fabrication',
            self::Ready        => 'Prêt',
            self::Delivered    => 'Livré',
            self::Cancelled    => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending      => 'yellow',
            self::Ordered      => 'blue',
            self::InProduction => 'purple',
            self::Ready        => 'teal',
            self::Delivered    => 'green',
            self::Cancelled    => 'red',
        };
    }
}
