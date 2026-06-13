<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait UserProvisioningTrait
{
    /**
     * Crea un usuario en `users` usando los datos base del modelo destino.
     *
     * @param string $role Rol a asignar en users (client|trainer)
     * @param string $name Nombre a usar en users
     * @param string $email Email a usar en users
     */
    protected function provisionUser(string $role, string $name, string $email): ?User
    {
        $defaultPassword = '12345678';

        $user = User::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($defaultPassword),
            'role' => $role,
        ]);
    }
}
