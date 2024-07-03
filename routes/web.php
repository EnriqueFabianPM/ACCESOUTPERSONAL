<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('estudiantes', ControladorEstudiante::class);
Route::resource('empleados', ControladorEmpleado::class);
Route::resource('visitantes', ControladorVisitante::class);