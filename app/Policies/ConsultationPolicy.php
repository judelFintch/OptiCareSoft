<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('consultations.view');
    }

    public function view(User $user, Consultation $consultation): bool
    {
        return $user->hasPermissionTo('consultations.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('consultations.create');
    }

    public function update(User $user, Consultation $consultation): bool
    {
        if ($consultation->isSigned()) {
            return false;
        }

        return $user->hasPermissionTo('consultations.edit')
            && $consultation->doctor_id === $user->id;
    }

    public function sign(User $user, Consultation $consultation): bool
    {
        return $user->hasPermissionTo('consultations.sign')
            && $consultation->doctor_id === $user->id;
    }

    public function delete(User $user, Consultation $consultation): bool
    {
        return $user->hasPermissionTo('consultations.delete')
            && $consultation->isDraft();
    }
}
