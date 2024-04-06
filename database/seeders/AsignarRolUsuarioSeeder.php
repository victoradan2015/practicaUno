<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsignarRolUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Asigna rol de administrador a usuario administrador
        $user = User::where('email', 'admin@gmail.com')->first();
        $user->assignRole('administrador');
    }
}
