<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',
            'notifications.publish',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $gestor = Role::firstOrCreate(['name' => 'gestor']);
        $gestor->givePermissionTo([
            'notifications.view',
            'notifications.create',
            'notifications.publish',
        ]);

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->givePermissionTo([
            'notifications.view',
            'notifications.edit',
        ]);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo([
            'notifications.view',
        ]);

        $invited = Role::firstOrCreate(['name' => 'invited']);
        // sin permisos
    }
}

