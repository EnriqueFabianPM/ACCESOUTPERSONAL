<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/estudiantes/show/{estudiante}', [ControladorEstudiante::class, 'show'])
    ->name('estudiantes.show');
Route::get('/empleados/show/{empleado}', [ControladorEmpleado::class, 'show'])
    ->name('empleados.show');
Route::get('/visitantes/show/{visitante}', [ControladorVisitante::class, 'show'])
    ->name('visitantes.show');

Route::resource('estudiantes', ControladorEstudiante::class);
Route::resource('empleados', ControladorEmpleado::class);
Route::resource('visitantes', ControladorVisitante::class);