<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios con diferentes roles
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Site.123'),
	    'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('admin');

        $gestor = User::create([
            'name' => 'Gestor User',
            'email' => 'gestor@example.com',
            'password' => Hash::make('Site.123'),
            'email_verified_at' => Carbon::now(),
        ]);
        $gestor->assignRole('gestor');

        $editor = User::create([
            'name' => 'Editor Uno',
            'email' => 'editor1@example.com',
            'password' => Hash::make('Site.123'),
            'email_verified_at' => Carbon::now(),
        ]);
        $editor->assignRole('editor');

         $editor2 = User::create([
            'name' => 'Editor Uno',
            'email' => 'editor2@example.com',
            'password' => Hash::make('Site.123'),
            'email_verified_at' => Carbon::now(),
        ]);
        $editor2->assignRole('editor');

        $user1 = User::create([
            'name' => 'User Dos',
            'email' => 'user1@example.com',
            'password' => Hash::make('Site.123'),
        ]);
        $user1->assignRole('user');

        $user2 = User::create([
            'name' => 'User Dos',
            'email' => 'user2@example.com',
            'password' => Hash::make('Site.123'),
        ]);
        $user2->assignRole('user');

        $invited1 = User::create([
            'name' => 'Invited Uno',
            'email' => 'invited1@example.com',
            'password' => Hash::make('Site.123'),
        ]);
        $invited1->assignRole('invited');

        $invited2 = User::create([
            'name' => 'Invited Dos',
            'email' => 'invited2@example.com',
            'password' => Hash::make('Site.123'),
        ]);

    }
}
