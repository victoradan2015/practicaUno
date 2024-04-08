@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
    <p>Usuarios.</p>

    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>    
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departamentos as $departamento)
                    <tr>
                        <td>{{$departamento->id}}</td>
                        <td>{{$departamento->nombre}}</td>
                        <td>{{$departamento->email}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
@stop

@section('css')
@stop

@section('js')
@stop