<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'Fotoqr',
        'identificador',
        'nombre',
        'apellidos',
        'motivo',
        'telefono',
        'email',
        'entrada',
        'salida',
    ];
    use HasFactory;
}