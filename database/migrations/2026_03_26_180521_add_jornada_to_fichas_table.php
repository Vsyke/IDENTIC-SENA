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
    Schema::table('fichas', function (Blueprint $table) {
        // Añadimos la jornada (Mañana, Tarde, Noche, etc.)
        $table->string('jornada')->nullable()->after('programa'); 
    });
}

public function down(): void
{
    Schema::table('fichas', function (Blueprint $table) {
        $table->dropColumn('jornada');
    });
}
};
