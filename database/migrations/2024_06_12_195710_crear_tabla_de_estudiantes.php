<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('Fotoqr')->nullable();
            $table->string('Foto')->nullable();
            $table->unsignedBigInteger('identificador');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('semestre');
            $table->string('grupo');
            $table->string('email')->unique();
            $table->datetime('entrada')->nullable();
            $table->datetime('salida')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
