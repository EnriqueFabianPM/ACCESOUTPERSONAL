<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'Fotoqr',
        'Foto',
        'identificador',
        'nombre',
        'apellidos',
        'semestre',
        'grupo',
        'email',
        'entrada',
        'salida',
    ];
    use HasFactory;
}
