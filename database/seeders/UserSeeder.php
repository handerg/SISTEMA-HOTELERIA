<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@hotel.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Recepcionista',
            'email'    => 'recepcion@hotel.com',
            'password' => bcrypt('password'),
            'role'     => 'recepcionista',
        ]);
    }
}
