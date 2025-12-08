<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // PERMISOS DEL SISTEMA VIEJO (SI AÚN LOS NECESITAS)
        $modulosViejos = [
            'unidades',
            'afectacion_tipos',
            'clientes',
            'documento_tipos',
            'productos',
            'ventas',
            'comprobante_tipos',
            'users',
            'roles_permisos',
            'compras',
            'proveedores',
            'aulas'
        ];

        // PERMISOS DEL SISTEMA EDUCATIVO NUEVO
        $modulosNuevos = [
            'aulas',
            'fichas',
            'estudiantes',
            'maestros'
        ];

        $actions = ['list', 'create', 'edit', 'delete'];

        $permissions = [];

        // Crear permisos del sistema viejo
        foreach ($modulosViejos as $modulo) {
            foreach ($actions as $action) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => "{$modulo}_{$action}"
                ]);
            }
        }

        // Crear permisos del sistema educativo
        foreach ($modulosNuevos as $modulo) {
            foreach ($actions as $action) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => "{$modulo}_{$action}"
                ]);
            }
        }

        // ============
        // ROLES
        // ============
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante']);
        $maestroRole = Role::firstOrCreate(['name' => 'maestro']);
        $vigilanteRole = Role::firstOrCreate(['name' => 'vigilante']);
        $invitadoRole = Role::firstOrCreate(['name' => 'invitado']);
        $vendedorRole = Role::firstOrCreate(['name' => 'vendedor']);

        // ======================
        // ASIGNAR PERMISOS
        // ======================

        // 🔥 ADMIN TIENE TODO 🔥
        $adminRole->syncPermissions($permissions);

        // Maestro solo administra fichas / aulas / estudiantes
        $maestroRole->syncPermissions([
            'aulas_list',
            'aulas_create',
            'aulas_edit',
            'aulas_delete',

            'fichas_list',
            'fichas_create',
            'fichas_edit',
            'fichas_delete',

            'estudiantes_list',
        ]);

        // Estudiante solo ve su información
        $estudianteRole->syncPermissions([
            'estudiantes_list'
        ]);

        // Admin por defecto
        $admin = User::firstOrCreate(
            ['email' => 'admin@prueba.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin'),
                'activo' => true
            ]
        );

        $admin->assignRole('admin');
    }
}
