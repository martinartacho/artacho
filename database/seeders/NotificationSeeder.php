<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios con roles específicos
        $admin = User::role('admin')->firstOrFail();
        $gestor = User::role('gestor')->firstOrFail();
        
        // Obtener los primeros dos editores
        $editors = User::role('editor')->take(2)->get();
        if ($editors->count() < 2) {
            $this->command->error('Se necesitan al menos 2 usuarios con rol editor');
            return;
        }
        
        // Obtener los primeros dos usuarios normales
        $regularUsers = User::role('user')->take(2)->get();
        if ($regularUsers->count() < 2) {
            $this->command->error('Se necesitan al menos 2 usuarios con rol user');
            return;
        }

        // 1. Notificación pública (admin a todos)
        Notification::create([
            'title' => 'Mantenimiento programado',
            'content' => 'El sistema estará inactivo el próximo sábado',
            'sender_id' => $admin->id,
            'recipient_type' => 'all',
            'is_published' => true,
            'published_at' => now(),
            'web_sent' => true
        ]);

        // 2. Notificación por rol (gestor a editores)
        Notification::create([
            'title' => 'Nuevas directrices editoriales',
            'content' => 'Por favor revisen las nuevas normas',
            'sender_id' => $gestor->id,
            'recipient_type' => 'role',
            'recipient_role' => 'editor',
            'is_published' => true,
            'published_at' => now()->subDay(),
            'web_sent' => true
        ]);

        // 3. Notificación específica (primer editor a dos usuarios)
        $specificNotification = Notification::create([
            'title' => 'Tu artículo ha sido aprobado',
            'content' => 'Felicitaciones por tu publicación',
            'sender_id' => $editors[0]->id,
            'recipient_type' => 'specific',
            'recipient_ids' => [$regularUsers[0]->id, $regularUsers[1]->id],
            'is_published' => true,
            'published_at' => now()->subHours(3),
            'web_sent' => true
        ]);
        $specificNotification->recipients()->attach([$regularUsers[0]->id, $regularUsers[1]->id]);

        // 4. Notificación pendiente (segundo editor sin publicar)
        Notification::create([
            'title' => 'Borrador: Cambios en políticas',
            'content' => 'Esta notificación está pendiente de revisión',
            'sender_id' => $editors[1]->id,
            'recipient_type' => 'all',
            'is_published' => false,
            'published_at' => null
        ]);

        $this->command->info('4 notificaciones de prueba creadas exitosamente');
    }
}