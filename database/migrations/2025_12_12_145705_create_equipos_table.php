<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');        // Ej: Portátil, Mouse, Teclado
            $table->string('marca_serie'); // Ej: HP / SN12345

            // Relación con el usuario (quien tiene el equipo)
            // onDelete('cascade') borra el equipo si se borra el usuario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
};
