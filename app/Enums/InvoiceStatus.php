<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft          = 'draft';
    case Unpaid         = 'unpaid';
    case PartiallyPaid  = 'partially_paid';
    case Paid           = 'paid';
    case Cancelled      = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Draft         => 'Brouillon',
            self::Unpaid        => 'Non payée',
            self::PartiallyPaid => 'Partiellement payée',
            self::Paid          => 'Payée',
            self::Cancelled     => 'Annulée',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft         => 'gray',
            self::Unpaid        => 'red',
            self::PartiallyPaid => 'yellow',
            self::Paid          => 'green',
            self::Cancelled     => 'slate',
        };
    }
}
