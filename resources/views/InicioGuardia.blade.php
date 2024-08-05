@extends('layouts.app')

@section('title', 'Inicio Guardia')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center">Página Principal del Guardia de Seguridad</h1>
            <div class="text-center mt-4">
                <div class="btn-group" role="group" aria-label="Acciones del Guardia">
                    <a href="{{ route('estudiantes.showEntradaForm', ['id' => 1]) }}" class="btn btn-primary btn-lg">Registrar Entrada de Estudiantes</a>
                    <a href="{{ route('empleados.showEntradaForm', ['id' => 1]) }}" class="btn btn-primary btn-lg">Registrar Entrada de Empleados</a>
                    <a href="{{ route('visitantes.showEntradaForm', ['id' => 1]) }}" class="btn btn-primary btn-lg">Registrar Entrada de Visitantes</a>
                    <a href="{{ route('estudiantes.showSalidaForm', ['id' => 1]) }}" class="btn btn-secondary btn-lg">Registrar Salida de Estudiantes</a>
                    <a href="{{ route('empleados.showSalidaForm', ['id' => 1]) }}" class="btn btn-secondary btn-lg">Registrar Salida de Empleados</a>
                    <a href="{{ route('visitantes.showSalidaForm', ['id' => 1]) }}" class="btn btn-secondary btn-lg">Registrar Salida de Visitantes</a>
                    <a href="{{ route('scanner') }}" class="btn btn-warning btn-lg">Escanear QR</a>
                    <a href="{{ route('estudiantes.log') }}" class="btn btn-info btn-lg">Ver Logs de Estudiantes</a>
                    <a href="{{ route('empleados.log') }}" class="btn btn-info btn-lg">Ver Logs de Empleados</a>
                    <a href="{{ route('visitantes.log') }}" class="btn btn-info btn-lg">Ver Logs de Visitantes</a>
                    <a href="{{ route('all.logs') }}" class="btn btn-success btn-lg">Ver Todos los Logs</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
