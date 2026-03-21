<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up(): void
{
    Schema::table('estudiantes', function (Blueprint $table) {
        // Creamos la columna y la conectamos con la tabla fichas
        $table->unsignedBigInteger('ficha_id')->nullable()->after('user_id');
        
        // Definimos la relación técnica
        $table->foreign('ficha_id')->references('id')->on('fichas')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('estudiantes', function (Blueprint $table) {
        $table->dropForeign(['ficha_id']);
        $table->dropColumn('ficha_id');
    });
}
};
