@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Empleados</h1>
@stop

@section('content')
    <p>Empelados.</p>

    <div class="d-flex justify-content-end">
        <button class="btn btn-success" type="submit" class="btn btn-primary" onclick="editarEmpleado()" data-toggle="modal" data-target="#nuevoEmpleadoModal">Nuevo Empleado</button>
    </div>

    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>    
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Numero</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Sueldo Diario</th>
                    <th>Departamento ID</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empleados as $empleado)
                    <tr>
                        <td>{{$empleado->id}}</td>
                        <td>{{$empleado->nombre}}</td>
                        <td>{{$empleado->apellido_paterno}}</td>
                        <td>{{$empleado->apellido_materno}}</td>
                        <td>{{$empleado->numero}}</td>
                        <td>{{$empleado->fecha_nacimiento}}</td>
                        <td>{{$empleado->sueldo_diario}}</td>
                        <td>{{$empleado->departamento->nombre}}</td>
                        <td>
                            <form action="{{ route('empleados.delete', ['id' => $empleado->id]) }}" method="POST">
                                <!-- @csrf -->
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modificarEmpleadoModal" onclick="editarEmpleado('{{ $empleado->id }}', '{{ $empleado->nombre }}', '{{ $empleado->apellido_paterno }}', '{{ $empleado->apellido_materno }}', '{{ $empleado->numero }}', '{{ $empleado->fecha_nacimiento }}', '{{ $empleado->sueldo_diario }}', '{{ $empleado->departamento->id }}')">Modificar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="modal fade" id="nuevoEmpleadoModal" tabindex="-1" role="dialog" aria-labelledby="empleadoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empleadoModalLabel">Nuevo Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('empleados.create') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group">
                        <label for="campo">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="campo">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno">
                    </div>
                    <div class="form-group">
                        <label for="campo">Apellido Materno:</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                    </div>
                    <div class="form-group">
                        <label for="campo">Numero:</label>
                        <input type="text" class="form-control" id="numero" name="numero">
                    </div>
                    <div class="form-group">
                        <label for="campo">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                    </div>
                    <div class="form-group">
                        <label for="campo">Sueldo Diario:</label>
                        <input type="text" class="form-control" id="sueldo_diario" name="sueldo_diario">
                    </div>
                    <div class="form-group">
                        <label for="departamento">Departamento:</label>
                        <select class="form-control" id="departamento" name="departamento_id">
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modificarEmpleadoModal" tabindex="-1" role="dialog" aria-labelledby="modificarEmpleadoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarEmpleadoModalLabel">Nuevo Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('empleados.update') }}" method="POST">
                        <!-- @csrf -->
                        @method('PUT')
                        <input type="hidden" name="id" id="id_empleado">
                        <div class="form-group">
                            <label for="campo">Nombre:</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre">
                        </div>
                        <div class="form-group">
                            <label for="campo">Apellido Paterno:</label>
                            <input type="text" class="form-control" id="edit_apellido_paterno" name="apellido_paterno">
                        </div>
                        <div class="form-group">
                            <label for="campo">Apellido Materno:</label>
                            <input type="text" class="form-control" id="edit_apellido_materno" name="apellido_materno">
                        </div>
                        <div class="form-group">
                            <label for="campo">Numero:</label>
                            <input type="text" class="form-control" id="edit_numero" name="numero">
                        </div>
                        <div class="form-group">
                            <label for="campo">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control" id="edit_fecha_nacimiento" name="fecha_nacimiento">
                        </div>
                        <div class="form-group">
                            <label for="campo">Sueldo Diario:</label>
                            <input type="text" class="form-control" id="edit_sueldo_diario" name="sueldo_diario">
                        </div>
                        <div class="form-group">
                            <label for="departamento">Departamento:</label>
                            <select class="form-control" id="edit_departamento" name="departamento_id">
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@stop

@section('js')
<script>
    function editarEmpleado(id, nombre, apellido_paterno, apellido_materno, numero, fecha_nacimiento, sueldo_diario, departamento_id) {
        $('#id_empleado').val(id);
        $('#edit_nombre').val(nombre);
        $('#edit_apellido_paterno').val(apellido_paterno);
        $('#edit_apellido_materno').val(apellido_materno);
        $('#edit_numero').val(numero);
        $('#edit_fecha_nacimiento').val(fecha_nacimiento);
        $('#edit_sueldo_diario').val(sueldo_diario);
        $('#edit_departamento').val(departamento_id);
    }
</script>
@stop