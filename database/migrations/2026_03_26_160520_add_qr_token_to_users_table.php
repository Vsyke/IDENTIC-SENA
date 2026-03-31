<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('qr_token')->unique()->nullable()->after('email');
        });

        \DB::table('users')->get()->each(function ($user) {
            \DB::table('users')
                ->where('id', $user->id)
                ->update(['qr_token' => (string) Str::uuid()]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};