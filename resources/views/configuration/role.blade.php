@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
    <p>Roles y Permisos.</p>

    <form action="{{ route('roles.update') }}" method="POST">
    @csrf
    @method('PUT')
        <table class="table">
            <thead>
                <tr>
                    <th>Rol</th>
                    @foreach ($permissions as $permission)
                        <th class="vertical-text">{{ $permission->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        @foreach ($permissions as $permission)
                            <td>
                                <input type="checkbox" name="permissions[{{ $role->id }}][{{ $permission->id }}]"
                                    {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">Actualizar Permisos</button>
        </div>
        
    </form>

    
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <style>
    .vertical-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }
    </style>
@stop

@section('js')
    <script> console.log(""); </script>
@stop