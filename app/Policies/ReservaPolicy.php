<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;

class ReservaPolicy
{
    /**
     * Solo admin o el creador pueden actualizar.
     */
    public function update(User $user, Reserva $reserva): bool
    {
        return $user->isAdmin() || $user->id === $reserva->user_id;
    }

    /**
     * Solo admin puede eliminar.
     */
    public function delete(User $user, Reserva $reserva): bool
    {
        return $user->isAdmin();
    }

    /**
     * Cualquier usuario autenticado puede crear.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Cualquier usuario autenticado puede ver.
     */
    public function view(User $user, Reserva $reserva): bool
    {
        return true;
    }
}
