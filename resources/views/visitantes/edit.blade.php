@extends('visitantes.layout')
@section('content')
<div class="card">
    <div class="card-header">Editar Información de Visitante</div>
    <div class="card-body">
        <form action="{{ route('visitantes.update', ['visitante' => $visitante->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="Fotoqr">Imagen de Codigo QR</label>
                <div>
                    @if($visitante->Fotoqr)
                        <img src="{{ asset($visitante->Fotoqr) }}" alt="Imagen QR" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
                <input type="file" class="form-control" id="Fotoqr" name="Fotoqr">
            </div>
            <div class="form-group">
                <label for="identificador">Identificador:</label>
                <input type="text" class="form-control" id="identificador" name="identificador" value="{{ $visitante->identificador }}" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $visitante->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $visitante->apellidos }}" required>
            </div>
            <div class="form-group">
                <label for="motivo">Motivo de Visita:</label>
                <input type="text" class="form-control" id="motivo" name="motivo" value="{{ $visitante->motivo }}" required>
            </div>
            <div class="form-group">
                <label for="telefono">Telefono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $visitante->telefono }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $visitante->email }}" required>
            </div>
            <div class="form-group">
                <label for="entrada">Entrada:</label>
                <input type="date" class="form-control" id="entrada" name="entrada" value="{{ $visitante->entrada }}">
            </div>
            <div class="form-group">
                <label for="salida">Salida:</label>
                <input type="date" class="form-control" id="salida" name="salida" value="{{ $visitante->salida }}">
            </div>
            <button type="submit" class="btn btn-success">Actualizar Registro</button>
        </form>
    </div>
</div>
@endsection
