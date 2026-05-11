<?php

namespace App\Enums;

enum StockMovementType: string
{
    case In         = 'in';
    case Out        = 'out';
    case Adjustment = 'adjustment';
    case Loss       = 'loss';
    case Return     = 'return';

    public function label(): string
    {
        return match($this) {
            self::In         => 'Entrée',
            self::Out        => 'Sortie',
            self::Adjustment => 'Ajustement',
            self::Loss       => 'Perte',
            self::Return     => 'Retour',
        };
    }

    public function isPositive(): bool
    {
        return in_array($this, [self::In, self::Return, self::Adjustment]);
    }
}
