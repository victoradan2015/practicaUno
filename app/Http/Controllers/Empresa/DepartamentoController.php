<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Departamento;

class DepartamentoController extends Controller
{
    public function viewListDepartamentos(){
        $departamentos = Departamento::all();

        return view('empresa/departamentos', compact('departamentos'));
        //return view('configuration.role', compact('roles', 'permissions'));
    }

    public function create(Request $request)
    {
        Departamento::create(['nombre' => $request->nombre,'descripcion' => $request->descripcion]);

        return redirect()->back()->with('success', 'Departamento Creado.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
        ]);

        $departamento = Departamento::findOrFail($request->departamento_id);
        $departamento->nombre = $request->nombre;
        $departamento->descripcion = $request->descripcion;
        $departamento->save();

        return redirect()->back()->with('success', 'Departamento Actualizado');
    }
    
    public function delete($id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return redirect()->back()->with('success', 'Departamento Eliminado.');
    }

}
