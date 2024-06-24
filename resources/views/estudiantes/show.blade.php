@extends('estudiantes.layout')
@section('content') 
    <div class="row">
        <div class="col-lg-6">
            <h2>Mostrar Información de Estudiante</h2>
        </div>
        <div class="col-lg-6 text-end">
            <a class="btn btn-primary" href="{{ route('estudiantes.index') }}"> Volver al Registro</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <table class="table table-bordered">
                <tr>
                    <th>Foto de Estudiante: </th>
                    <td><img src="/FotosEstudiantes/{{ $estudiante->Foto }}<" width="500px" height="10000px"></td>
                </tr>
                <tr>
                    <th>imagen de Codigo QR: </th>
                    <td><img src="/ImagenesQREstudiantes/{{ $estudiante->Fotoqr }}" width="100px"></td>
                </tr>
                <tr>
                    <th>Identificador: </th>
                    <td>{{ $estudiante->identificador }}</td>
                </tr>
                <tr>
                    <th>Nombre: </th>
                    <td>{{ $estudiante->nombre }}</td>
                </tr>
                <tr>
                    <th>Apellidos: </th>
                    <td>{{ $estudiante->apellidos }}</td>
                </tr>
                <tr>
                    <th>Semestre: </th>
                    <td>{{ $estudiante->semestre }}</td>
                </tr>
                <tr>
                    <th>Grupo: </th>
                    <td>{{ $estudiante->grupo }}</td>
                </tr>
                <tr>
                    <th>Email: </th>
                    <td>{{ $estudiante->email }}</td>
                </tr>
                <tr>
                    <th>Entrada: </th>
                    <td>{{ $estudiante->entrada }}</td>
                </tr>
                <tr>
                    <th>Salida: </th>
                    <td>{{ $estudiante->salida }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection