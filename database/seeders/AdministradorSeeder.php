<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    /**
     * Crea un administrador con contrasenia del 12345.
     */
    public function run(): void
    {
        User::create([
            'name' => 'administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'rol' => 0,
        ]);
    }
}
