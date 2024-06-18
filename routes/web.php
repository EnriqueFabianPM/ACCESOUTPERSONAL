<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorEstudiante;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('estudiante', ControladorEstudiante::class);