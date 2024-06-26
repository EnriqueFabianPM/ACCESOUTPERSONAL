@extends('estudiantes.layout')
@section('content')
<div class="card">
    <div class="card-header">Editar Información de Estudiante</div>
    <div class="card-body">
        <form action="{{ route('estudiantes.update', ['estudiante' => $estudiante->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="Fotoqr">Imagen de Codigo QR</label>
                <div>
                    @if($estudiante->Fotoqr)
                        <img src="{{ asset($estudiante->Fotoqr) }}" alt="Imagen QR" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
                <input type="file" class="form-control" id="Fotoqr" name="Fotoqr">
            </div>
            <div class="form-group">
                <label for="Foto">Foto de Estudiante</label>
                <div>
                    @if($estudiante->Foto)
                        <img src="{{ asset($estudiante->Foto) }}" alt="Foto del Estudiante" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
                <input type="file" class="form-control" id="Foto" name="Foto">
            </div>
            <div class="form-group">
                <label for="identificador">Identificador:</label>
                <input type="text" class="form-control" id="identificador" name="identificador" value="{{ $estudiante->identificador }}" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $estudiante->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $estudiante->apellidos }}" required>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre:</label>
                <input type="text" class="form-control" id="semestre" name="semestre" value="{{ $estudiante->semestre }}" required>
            </div>
            <div class="form-group">
                <label for="grupo">Grupo:</label>
                <input type="text" class="form-control" id="grupo" name="grupo" value="{{ $estudiante->grupo }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $estudiante->email }}" required>
            </div>
            <div class="form-group">
                <label for="entrada">Entrada:</label>
                <input type="date" class="form-control" id="entrada" name="entrada" value="{{ $estudiante->entrada }}">
            </div>
            <div class="form-group">
                <label for="salida">Salida:</label>
                <input type="date" class="form-control" id="salida" name="salida" value="{{ $estudiante->salida }}">
            </div>
            <button type="submit" class="btn btn-success">Actualizar Registro</button>
        </form>
    </div>
</div>
@endsection
