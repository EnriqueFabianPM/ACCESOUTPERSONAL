<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;

Route::get('/', function () {
    return view('welcome');
})->name('Inicio');

// Custom SaveQRs routes
Route::post('/estudiantes/save-qrcode', [ControladorEstudiante::class, 'saveQRCode'])->name('estudiantes.save.qrcode');
Route::post('/empleados/save-qrcode', [ControladorEmpleado::class, 'saveQRCode'])->name('empleados.save.qrcode');
Route::post('/visitantes/save-qrcode', [ControladorVisitante::class, 'saveQRCode'])->name('visitantes.save.qrcode');

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
