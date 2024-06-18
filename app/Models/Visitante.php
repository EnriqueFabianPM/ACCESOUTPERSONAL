<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    protected $table = 'empleados' ;
    protected $primarykey = 'id' ;
    protected $fillable = ['Fotoqr', 'Foto', 'identificador', 'nombre', 'apellidos', 'telefono', 'email', 'entrada', 'salida'];
    use HasFactory;
}
