<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('invoices.view');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('invoices.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('invoices.create');
    }

    public function cancel(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('invoices.cancel')
            && ! $invoice->isCancelled();
    }

    public function receivePayment(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('payments.receive')
            && $invoice->canBePaid();
    }
}
