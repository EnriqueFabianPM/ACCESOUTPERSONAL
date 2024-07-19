@extends('visitantes.layout')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <h2>Registrar Entrada</h2>
    </div>
    <div class="col-lg-6 text-end">
        <a class="btn btn-primary" href="{{ route('visitantes.index') }}">Volver al Registro</a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Identificador:</th>
                <td>{{ $visitante->identificador }}</td>
            </tr>
            <tr>
                <th>Imagen de Código QR:</th>
                <td><img src="{{ asset($visitante->Fotoqr) }}" alt="Código QR de {{ $visitante->nombre }}" width="100px"></td>
            </tr>
            <tr>
                <th>Nombre:</th>
                <td>{{ $visitante->nombre }}</td>
            </tr>
            <tr>
                <th>Apellidos:</th>
                <td>{{ $visitante->apellidos }}</td>
            </tr>
            <tr>
                <th>Motivo de Visita:</th>
                <td>{{ $visitante->motivo }}</td>
            </tr>
            <tr>
                <th>Telefono:</th>
                <td>{{ $visitante->telefono }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $visitante->email }}</td>
            </tr>
            <tr>
                <th>Entrada:</th>
                <td>{{ $visitante->entrada ? $visitante->entrada->format('d/m/Y H:i:s') : 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Salida:</th>
                <td>{{ $visitante->salida ? $visitante->salida->format('d/m/Y H:i:s') : 'No registrado' }}</td>
            </tr>
        </table>

        @if(!$visitante->entrada)
        <form action="{{ route('visitantes.registerEntrada', $visitante->identificador) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Registrar Entrada</button>
        </form>
        @endif
    </div>
</div>
@endsection
