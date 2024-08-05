<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'user_email',
        'table_name', // The name of the table where the action occurred
        'action',
        'record_id', // ID of the record being changed
        'old_data',
        'new_data',
    ];

    // Cast JSON fields to array
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    // Optional: Define the table name if it doesn't follow Laravel's conventions
    protected $table = 'logs';
}
