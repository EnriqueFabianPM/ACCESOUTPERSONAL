@extends('empleados.layout')
@section('content')
<div class="card">
    <div class="card-header">Editar Información de Estudiante</div>
    <div class="card-body">
        <form action="{{ route('empleados.update', ['empleado' => $empleado->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="Fotoqr">Imagen de Codigo QR</label>
                <div>
                    @if($empleado->Fotoqr)
                        <img src="{{ asset($empleado->Fotoqr) }}" alt="Imagen QR" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
                <input type="file" class="form-control" id="Fotoqr" name="Fotoqr">
            </div>
            <div class="form-group">
                <label for="Foto">Foto de Empleado</label>
                <div>
                    @if($empleado->Foto)
                        <img src="{{ asset($empleado->Foto) }}" alt="Foto del Empleado" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
                <input type="file" class="form-control" id="Foto" name="Foto">
            </div>
            <div class="form-group">
                <label for="identificador">Identificador:</label>
                <input type="text" class="form-control" id="identificador" name="identificador" value="{{ $empleado->identificador }}" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $empleado->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $empleado->apellidos }}" required>
            </div>
            <div class="form-group">
                <label for="areatrabajo">Area de Trabajo:</label>
                <input type="text" class="form-control" id="areatrabajo" name="areatrabajo" value="{{ $empleado->areatrabajo }}" required>
            </div>
            <div class="form-group">
                <label for="telefono">Telefono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $empleado->telefono }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $empleado->email }}" required>
            </div>
            <div class="form-group">
                <label for="entrada">Entrada:</label>
                <input type="date" class="form-control" id="entrada" name="entrada" value="{{ $empleado->entrada }}">
            </div>
            <div class="form-group">
                <label for="salida">Salida:</label>
                <input type="date" class="form-control" id="salida" name="salida" value="{{ $empleado->salida }}">
            </div>
            <button type="submit" class="btn btn-success">Actualizar Registro</button>
        </form>
    </div>
</div>
@endsection
