<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Verificar si el total de usuarios es mayor a 3
        if (User::count() > 3) {
            // Asignar el rol por defecto al usuario recién creado, si existe
            $defaultRole = Role::where('name', 'user')->first(); // Puedes cambiar el nombre del rol aquí
            if ($defaultRole) {
                $user->assignRole($defaultRole->name);
            }
        }
    }
}