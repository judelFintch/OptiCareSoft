<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash        = 'cash';
    case MobileMoney = 'mobile_money';
    case Bank        = 'bank';
    case Card        = 'card';
    case Other       = 'other';

    public function label(): string
    {
        return match($this) {
            self::Cash        => 'Espèces',
            self::MobileMoney => 'Mobile Money',
            self::Bank        => 'Virement bancaire',
            self::Card        => 'Carte bancaire',
            self::Other       => 'Autre',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Cash        => '💵',
            self::MobileMoney => '📱',
            self::Bank        => '🏦',
            self::Card        => '💳',
            self::Other       => '💰',
        };
    }
}
