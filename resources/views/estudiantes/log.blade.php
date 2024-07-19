@extends('estudiantes.layout')

@section('content')
<div class="row mb-4">
    <div class="col-lg-6">
        <h2>Registro de Actividades de Estudiantes</h2>
    </div>
    <div class="col-lg-6 text-end">
        <a class="btn btn-primary" href="{{ route('estudiantes.index') }}">Volver al Registro</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6">
        <form action="{{ route('log.filter') }}" method="get">
            <div class="form-group">
                <label for="tipo">Filtrar por:</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="">Seleccione una opción</option>
                    <option value="entrada">Entradas</option>
                    <option value="salida">Salidas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3>Registros</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->identificador }}</td>
                            <td>{{ $log->nombre }}</td>
                            <td>{{ $log->tipo }}</td>
                            <td>{{ $log->hora }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
