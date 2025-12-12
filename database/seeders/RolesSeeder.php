<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la tabla para evitar duplicados
        DB::table('roles')->truncate();

        // Insertar roles con timestamps
        DB::table('roles')->insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'estudiante', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'invitado', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
