<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;

Route::get('/', function () {
    return view('welcome');
})->name('Inicio');

// Custom save QR code routes
Route::post('/save-qrcode-estudiante', [ControladorEstudiante::class, 'saveQRCode'])->name('save.qrcode.estudiante');
Route::post('/save-qrcode-empleado', [ControladorEmpleado::class, 'saveQRCode'])->name('save.qrcode.empleado');
Route::post('/save-qrcode-visitante', [ControladorVisitante::class, 'saveQRCode'])->name('save.qrcode.visitante');

// Custom show routes
Route::get('/estudiantes/show/{identificador}', [ControladorEstudiante::class, 'show'])->name('estudiantes.show');
Route::get('/empleados/show/{identificador}', [ControladorEmpleado::class, 'show'])->name('empleados.show');
Route::get('/visitantes/show/{identificador}', [ControladorVisitante::class, 'show'])->name('visitantes.show');

// Custom edit routes
Route::get('/estudiantes/edit/{identificador}', [ControladorEstudiante::class, 'edit'])->name('estudiantes.edit');
Route::get('/empleados/edit/{identificador}', [ControladorEmpleado::class, 'edit'])->name('empleados.edit');
Route::get('/visitantes/edit/{identificador}', [ControladorVisitante::class, 'edit'])->name('visitantes.edit');

// Custom delete routes
Route::delete('/estudiantes/{identificador}', [ControladorEstudiante::class, 'destroy'])->name('estudiantes.destroy');
Route::delete('/empleados/{identificador}', [ControladorEmpleado::class, 'destroy'])->name('empleados.destroy');
Route::delete('/visitantes/{identificador}', [ControladorVisitante::class, 'destroy'])->name('visitantes.destroy');

// Resourceful routes for CRUD operations
Route::resource('estudiantes', ControladorEstudiante::class)->except(['show', 'edit', 'destroy']);
Route::resource('empleados', ControladorEmpleado::class)->except(['show', 'edit', 'destroy']);
Route::resource('visitantes', ControladorVisitante::class)->except(['show', 'edit', 'destroy']);
