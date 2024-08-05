<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;
use App\Http\Controllers\ControladorEscaner;
use App\Http\Controllers\ControladorGuardia;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

// Main welcome page
Route::get('/', function () {
    return view('welcome');
})->name('Inicio');

// Authentication routes
Auth::routes();

// Route for QR code scanner page
Route::get('/scanner', [ControladorGuardia::class, 'scanner'])->name('scanner');

// Route to get IP address
Route::get('/get-ip', function () {
    return response()->json(['ip' => request()->ip()]);
});

// Route for handling QR code scans
Route::get('/scan/{qrCode}', [ControladorEscaner::class, 'handleScan'])->name('scan.handle');

// Custom Save QR Codes routes
Route::post('/estudiantes/save-qrcode', [ControladorEstudiante::class, 'saveQRCode'])->name('estudiantes.save.qrcode');
Route::post('/empleados/save-qrcode', [ControladorEmpleado::class, 'saveQRCode'])->name('empleados.save.qrcode');
Route::post('/visitantes/save-qrcode', [ControladorVisitante::class, 'saveQRCode'])->name('visitantes.save.qrcode');

// Route for logs
Route::get('/estudiantes/log', [ControladorEstudiante::class, 'log'])->name('estudiantes.log');
Route::get('/empleados/log', [ControladorEmpleado::class, 'log'])->name('empleados.log');
Route::get('/visitantes/log', [ControladorVisitante::class, 'log'])->name('visitantes.log');

// Custom Entrada and Salida routes using ControladorGuardia
Route::post('/register-entrada/{type}', [ControladorGuardia::class, 'registerEntrada'])->name('register.entrada');
Route::post('/register-salida/{type}', [ControladorGuardia::class, 'registerSalida'])->name('register.salida');

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

// Home route
Route::get('/home', [HomeController::class, 'index'])->name('home');
