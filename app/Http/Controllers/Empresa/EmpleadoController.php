<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Empleado;
use App\Models\Departamento;

class EmpleadoController extends Controller
{
    public function viewListEmpleados(){
        $empleados = Empleado::all();
        //Para el select anidado
        $departamentos = Departamento::all();


        return view('empresa/empleados', compact('empleados', 'departamentos'));
        //return view('configuration.role', compact('roles', 'permissions'));
    }

    public function create(Request $request)
    {
        //dd($request);
        Empleado::create([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'numero' => $request->numero,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sueldo_diario' => $request->sueldo_diario,
            'departamento_id' => $request->departamento_id,
        ]);

        return redirect()->back()->with('success', 'Empleado Creado.');
    }

    public function update(Request $request)
    {
        //dd($request);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
        ]);

        $empleado = Empleado::findOrFail($request->id);
        $empleado->nombre = $request->nombre;
        $empleado->apellido_paterno = $request->apellido_paterno;
        $empleado->apellido_materno = $request->apellido_materno;
        $empleado->numero = $request->numero;
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->sueldo_diario = $request->sueldo_diario;
        $empleado->departamento_id = $request->departamento_id;

        $empleado->save();
        
        return redirect()->back()->with('success', 'Empleado Actualizado');
    }

    public function delete($id)
    {
        $departamento = Empleado::findOrFail($id);
        $departamento->delete();

        return redirect()->back()->with('success', 'Empleado Eliminado.');
    }

}
