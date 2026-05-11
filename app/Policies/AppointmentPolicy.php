<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('appointments.view');
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $user->hasPermissionTo('appointments.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('appointments.create');
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->hasPermissionTo('appointments.edit');
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->hasPermissionTo('appointments.cancel');
    }
}
