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

        // Permisos para módulos básicos
        $permissions = [

            'admin.access',
            'settings.edit',

            'users.index',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',


            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.delete',

            'permissions.index',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

             // Notifications
            'notifications.publish',
            'notifications.index',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',
            'notifications.view',

            // Event permissions
            'events.index',
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
                
            // Event Type permissions
            'event_types.index',
            'event_types.view',
            'event_types.create',
            'event_types.edit',
            'event_types.delete',

            // Event Question permissions
            'event_questions.index',
            'event_questions.view',
            'event_questions.create',
            'event_questions.edit',
            'event_questions.delete',

            // Event Answer permissions
            'event_answers.index',
            'event_answers.view',
            'event_answers.create',
            'event_answers.edit',
            'event_answers.delete',

           

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }


        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        //gestor
        $gestor = Role::firstOrCreate(['name' => 'gestor']);

        $gestor->givePermissionTo(['users.index']);
        $gestor->givePermissionTo(['users.create']);
        $gestor->givePermissionTo(['users.edit']);
        $gestor->givePermissionTo(['users.delete']);

        $gestor->givePermissionTo(['notifications.index']);
        $gestor->givePermissionTo(['notifications.create']);
        $gestor->givePermissionTo(['notifications.edit']);
        $gestor->givePermissionTo(['notifications.delete']);
        $gestor->givePermissionTo(['notifications.view']);


        $gestor->givePermissionTo(['events.index']);
        $gestor->givePermissionTo(['events.create']);
        $gestor->givePermissionTo(['events.edit']);
        $gestor->givePermissionTo(['events.delete']);
        $gestor->givePermissionTo(['events.view']);

        $gestor->givePermissionTo(['event_questions.index']);
        $gestor->givePermissionTo(['event_questions.create']);
        $gestor->givePermissionTo(['event_questions.edit']);
        $gestor->givePermissionTo(['event_questions.delete']);
        $gestor->givePermissionTo(['event_questions.view']);

        $gestor->givePermissionTo(['events.index']);
        $gestor->givePermissionTo(['events.create']);
        $gestor->givePermissionTo(['events.edit']);
        $gestor->givePermissionTo(['events.delete']);
        $gestor->givePermissionTo(['events.view']);

        // editor
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->givePermissionTo(['notifications.index']);
        $editor->givePermissionTo(['notifications.create']);
        $editor->givePermissionTo(['notifications.edit']);
        $editor->givePermissionTo(['notifications.delete']);
        $editor->givePermissionTo(['notifications.view']);

        $editor->givePermissionTo(['events.index']);
        $editor->givePermissionTo(['events.create']);
        $editor->givePermissionTo(['events.edit']);
        $editor->givePermissionTo(['events.delete']);
        $editor->givePermissionTo(['events.view']);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo(['notifications.view']);
        $user->givePermissionTo(['notifications.create']);


        $invited = Role::firstOrCreate(['name' => 'invited']);
        $user->givePermissionTo(['notifications.index']);
        $user->givePermissionTo(['notifications.view']);

        // sin permisos

        // Asignar admin al usuario con ID = 1
        $user1 = \App\Models\User::find(1);
        if ($user1 && !$user1->hasRole('admin')) {
            $user1->assignRole($admin);
        }
    }
}

