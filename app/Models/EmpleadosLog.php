<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadosLog extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention
    protected $table = 'empleados_logs';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'user_email',
        'action',
        'empleado_id',
        'old_data',
        'new_data',
    ];

    // Cast JSON fields to array
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
}
