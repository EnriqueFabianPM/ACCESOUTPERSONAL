<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;
use App\Http\Controllers\ControladorEscaner;
use App\Http\Controllers\ControladorGuardia;

Route::get('/', function () {
    return view('welcome');
})->name('Inicio');

// Route for QR code scanner page
Route::get('/scanner', function () {
    return view('scanner');
})->name('scanner');

// Security Guard Main Page
Route::get('/inicio-guardia', [ControladorGuardia::class, 'index'])->name('inicio.guardias');

// QR Scanner
Route::get('/scanner', [ControladorGuardia::class, 'scanner'])->name('scanner');

// Logs
Route::get('/all/logs', [ControladorGuardia::class, 'allLogs'])->name('all.logs');

// Register Entrada
Route::post('/register-entrada/{type}', [ControladorGuardia::class, 'registerEntrada'])->name('register.entrada');

// Register Salida
Route::post('/register-salida/{type}', [ControladorGuardia::class, 'registerSalida'])->name('register.salida');

// Routes for logs
Route::get('/estudiantes/log', [ControladorEstudiante::class, 'log'])->name('estudiantes.log');
Route::get('/empleados/log', [ControladorEmpleado::class, 'log'])->name('empleados.log');
Route::get('/visitantes/log', [ControladorVisitante::class, 'log'])->name('visitantes.log');

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

// Custom Entrada and Salida routes for Estudiantes, Empleados, and Visitantes
Route::get('/estudiantes/entrada/{id}', [ControladorEstudiante::class, 'showEntradaForm'])->name('estudiantes.entrada');
Route::post('/estudiantes/entrada/{id}', [ControladorEstudiante::class, 'storeEntrada'])->name('estudiantes.storeEntrada');
Route::get('/estudiantes/salida/{id}', [ControladorEstudiante::class, 'showSalidaForm'])->name('estudiantes.salida');
Route::post('/estudiantes/salida/{id}', [ControladorEstudiante::class, 'storeSalida'])->name('estudiantes.storeSalida');
Route::get('/estudiantes/log', [ControladorEstudiante::class, 'log'])->name('estudiantes.log');

Route::get('/empleados/entrada/{id}', [ControladorEmpleado::class, 'showEntradaForm'])->name('empleados.entrada');
Route::post('/empleados/entrada/{id}', [ControladorEmpleado::class, 'storeEntrada'])->name('empleados.storeEntrada');
Route::get('/empleados/salida/{id}', [ControladorEmpleado::class, 'showSalidaForm'])->name('empleados.salida');
Route::post('/empleados/salida/{id}', [ControladorEmpleado::class, 'storeSalida'])->name('empleados.storeSalida');
Route::get('/empleados/log', [ControladorEmpleado::class, 'log'])->name('empleados.log');

Route::get('/visitantes/entrada/{id}', [ControladorVisitante::class, 'showEntradaForm'])->name('visitantes.entrada');
Route::post('/visitantes/entrada/{id}', [ControladorVisitante::class, 'storeEntrada'])->name('visitantes.storeEntrada');
Route::get('/visitantes/salida/{id}', [ControladorVisitante::class, 'showSalidaForm'])->name('visitantes.salida');
Route::post('/visitantes/salida/{id}', [ControladorVisitante::class, 'storeSalida'])->name('visitantes.storeSalida');
Route::get('/visitantes/log', [ControladorVisitante::class, 'log'])->name('visitantes.log');

// Resourceful routes for CRUD operations
Route::resource('estudiantes', ControladorEstudiante::class)->except(['show', 'edit', 'destroy']);
Route::resource('empleados', ControladorEmpleado::class)->except(['show', 'edit', 'destroy']);
Route::resource('visitantes', ControladorVisitante::class)->except(['show', 'edit', 'destroy']);
