@extends('visitantes.layout')

@section('content')
<div class="row mb-4">
    <div class="col-lg-6">
        <h2>Registro de Actividades de Visitantes</h2>
    </div>
    <div class="col-lg-6 text-end">
        <a class="btn btn-primary" href="{{ route('visitantes.index') }}">Volver al Registro</a>
    </div>
</div>

<div class="row">
    <h1>Registros(Logs): </h1>

    <!-- Table to display logs -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID de Usuario</th>
                <th>Email de Usuario</th>
                <th>Tabla (CRUD)</th>
                <th>Accion Utilizada</th>
                <th>Fecha / Hora</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->email }}</td>
                <td>{{ $log->table }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay registros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination links -->
    {{ $logs->links() }}
</div>
@endsection
