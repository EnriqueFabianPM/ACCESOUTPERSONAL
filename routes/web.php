<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;
use App\Http\Controllers\ControladorEmpleado;
use App\Http\Controllers\ControladorVisitante;
use App\Http\Controllers\ControladorEscaner;
use App\Http\Controllers\ControladorGuardia;

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('Inicio');

// Security Guard Main Page
Route::get('/inicio-guardia', [ControladorGuardia::class, 'index'])->name('InicioGuardia');

// QR Scanner
Route::get('/scanner', [ControladorGuardia::class, 'scanner'])->name('scanner');

// Logs
Route::get('/all/logs', [ControladorGuardia::class, 'allLogs'])->name('all.logs');

// Route for handling QR code scans
Route::post('/handle-scan', [ControladorGuardia::class, 'handleScan'])->name('handle.scan');

// Entry and Exit forms
Route::prefix('estudiantes')->group(function () {
    Route::get('/entrada/{id}', [ControladorEstudiante::class, 'showEntradaForm'])->name('estudiantes.showEntradaForm');
    Route::post('/entrada/{id}', [ControladorEstudiante::class, 'storeEntrada'])->name('estudiantes.storeEntrada');
    Route::get('/salida/{id}', [ControladorEstudiante::class, 'showSalidaForm'])->name('estudiantes.showSalidaForm');
    Route::post('/salida/{id}', [ControladorEstudiante::class, 'storeSalida'])->name('estudiantes.storeSalida');
});

Route::prefix('empleados')->group(function () {
    Route::get('/entrada/{id}', [ControladorEmpleado::class, 'showEntradaForm'])->name('empleados.showEntradaForm');
    Route::post('/entrada/{id}', [ControladorEmpleado::class, 'storeEntrada'])->name('empleados.storeEntrada');
    Route::get('/salida/{id}', [ControladorEmpleado::class, 'showSalidaForm'])->name('empleados.showSalidaForm');
    Route::post('/salida/{id}', [ControladorEmpleado::class, 'storeSalida'])->name('empleados.storeSalida');
});

Route::prefix('visitantes')->group(function () {
    Route::get('/entrada/{id}', [ControladorVisitante::class, 'showEntradaForm'])->name('visitantes.showEntradaForm');
    Route::post('/entrada/{id}', [ControladorVisitante::class, 'storeEntrada'])->name('visitantes.storeEntrada');
    Route::get('/salida/{id}', [ControladorVisitante::class, 'showSalidaForm'])->name('visitantes.showSalidaForm');
    Route::post('/salida/{id}', [ControladorVisitante::class, 'storeSalida'])->name('visitantes.storeSalida');
});

// Resourceful routes for CRUD operations
Route::resource('estudiantes', ControladorEstudiante::class)->except(['show', 'edit', 'destroy']);
Route::resource('empleados', ControladorEmpleado::class)->except(['show', 'edit', 'destroy']);
Route::resource('visitantes', ControladorVisitante::class)->except(['show', 'edit', 'destroy']);

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

// Logs routes
Route::get('/estudiantes/log', [ControladorEstudiante::class, 'log'])->name('estudiantes.log');
Route::get('/empleados/log', [ControladorEmpleado::class, 'log'])->name('empleados.log');
Route::get('/visitantes/log', [ControladorVisitante::class, 'log'])->name('visitantes.log');
