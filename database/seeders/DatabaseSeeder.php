<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador de desarrollo.
        User::updateOrCreate(
            ['email' => 'admin@ejemplo.com'],
            [
                'name'     => 'Coordinador CLE',
                'password' => 'Admin123',  
                'activo'   => true,
            ]
        );
    }
}