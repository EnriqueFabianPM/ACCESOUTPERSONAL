<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $table = 'estudiantes' ;
    protected $primarykey = 'id' ;
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
        'salida'
    ];
}
