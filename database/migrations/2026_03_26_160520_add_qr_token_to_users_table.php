<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Str;

User::all()->each(function ($user) {
    $user->update(['qr_token' => Str::uuid()]);
});

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Unique para que nadie tenga el mismo y nullable por si ya tienes usuarios creados
        $table->string('qr_token')->unique()->nullable()->after('email');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
