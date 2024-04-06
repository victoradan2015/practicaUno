<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Departamento;
use App\Models\Empleado;

class DepartementoYEmpleadoSeeder extends Seeder
{
    /**
     * Crea un departamento y un empleado con ese departamento.
     */
    public function run(): void
    {
        Departamento::create(['nombre' => 'Finanzas','descripcion' => 'Departamento de finanzas']);

        $departamento = Departamento::where('nombre', 'Finanzas')->first();

        //Crea empleado con departamento 1
        Empleado::create([
            'nombre' => 'Juan',
            'apellido_paterno' => 'Gonzales',
            'apellido_materno' => 'Perez',
            'numero' => '8776385478',
            'fecha_nacimiento' => '2024-03-27 14:51:03',
            'sueldo_diario' => '380.33',
            'departamento_id' => $departamento->id,
        ]);

    }
}