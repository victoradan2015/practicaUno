@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Departamentos</h1>
@stop

@section('content')
    <p>Departamentos.</p>

    <div class="d-flex justify-content-end">
        <button class="btn btn-success" type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevoDepartamentoModal">Nuevo Departamento</button>
    </div>

    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>    
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departamentos as $departamento)
                    <tr>
                        <td>{{$departamento->id}}</td>
                        <td>{{$departamento->nombre}}</td>
                        <td>{{$departamento->descripcion}}</td>
                        <td>
                            <form action="{{ route('departamentos.delete', ['id' => $departamento->id]) }}" method="POST">
                                <!-- @csrf -->
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modificarDepartamentoModal" onclick="editarDepartamento('{{ $departamento->id }}', '{{ $departamento->nombre }}', '{{ $departamento->descripcion }}')">Modificar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        


    <div class="modal fade" id="nuevoDepartamentoModal" tabindex="-1" role="dialog" aria-labelledby="departamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departamentoModalLabel">Nuevo Departamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('departamentos.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="campo">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre">
                </div>
                <div class="form-group">
                    <label for="campo">Descripcion:</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion">
                </div>

                <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modificarDepartamentoModal" tabindex="-1" role="dialog" aria-labelledby="modificarDepartamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificarDepartamentoModalLabel">Modificar Departamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('departamentos.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="departamento_id" id="departamento_id">
                <div class="form-group">
                    <label for="nombre_modificar">Nombre:</label>
                    <input type="text" class="form-control" id="nombre_modificar" name="nombre">
                </div>
                <div class="form-group">
                    <label for="descripcion_modificar">Descripción:</label>
                    <input type="text" class="form-control" id="descripcion_modificar" name="descripcion">
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
            </div>
        </div>
    </div>
</div>

@stop


@section('js')
<script>
    function editarDepartamento(id, nombre, descripcion) {
        $('#departamento_id').val(id);
        $('#nombre_modificar').val(nombre);
        $('#descripcion_modificar').val(descripcion);
    }
</script>
@stop