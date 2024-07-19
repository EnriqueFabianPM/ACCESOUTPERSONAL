@extends('empleados.layout')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <h2>Registrar Entrada</h2>
    </div>
    <div class="col-lg-6 text-end">
        <a class="btn btn-primary" href="{{ route('empleados.index') }}">Volver al Registro</a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Identificador:</th>
                <td>{{ $empleado->identificador }}</td>
            </tr>
            <tr>
                <th>Imagen de Código QR:</th>
                <td><img src="{{ asset($empleado->Fotoqr) }}" alt="Código QR de {{ $empleado->nombre }}" width="100px"></td>
            </tr>
            <tr>
                <th>Foto de Empleado:</th>
                <td><img src="{{ asset($empleado->Foto) }}" alt="Foto de {{ $empleado->nombre }}" width="100px"></td>
            </tr>
            <tr>
                <th>Nombre:</th>
                <td>{{ $empleado->nombre }}</td>
            </tr>
            <tr>
                <th>Apellidos:</th>
                <td>{{ $empleado->apellidos }}</td>
            </tr>
            <tr>
                <th>Area de Trabajo:</th>
                <td>{{ $empleado->areatrabajo }}</td>
            </tr>
            <tr>
                <th>Telefono:</th>
                <td>{{ $empleado->telefono }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $empleado->email }}</td>
            </tr>
            <tr>
                <th>Entrada:</th>
                <td>{{ $empleado->entrada ? $empleado->entrada->format('d/m/Y H:i:s') : 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Salida:</th>
                <td>{{ $empleado->salida ? $empleado->salida->format('d/m/Y H:i:s') : 'No registrado' }}</td>
            </tr>
        </table>

        @if($empleado->entrada && !$empleado->salida)
        <form action="{{ route('estudiantes.registerSalida', $empleado->identificador) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning">Registrar Salida</button>
        </form>
        @elseif(!$empleado->entrada)
        <p class="text-danger">No se puede registrar salida sin entrada.</p>
        @endif
    </div>
</div>
@endsection
