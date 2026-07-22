<?php

namespace App\Policies;

use App\Models\Servicio;
use App\Models\User;

class ServicioPolicy
{
    /**
     * Solo admin puede modificar precios.
     */
    public function update(User $user, Servicio $servicio): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede eliminar servicios.
     */
    public function delete(User $user, Servicio $servicio): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede crear servicios.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Cualquier usuario autenticado puede ver servicios.
     */
    public function view(User $user, Servicio $servicio): bool
    {
        return true;
    }
}
