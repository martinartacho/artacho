<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PushLogController;
use App\Http\Controllers\Gestor\GestorController;
use App\Http\Controllers\Gestor\UserController as GestorUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventTypeController;
// use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\EventQuestionController;
use App\Http\Controllers\Admin\EventAnswerController;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;



// Language
Route::post('/set-locale', [LocaleController::class, 'set'])->name('set-locale');
Route::post('/language/resolve-conflict', [LocaleController::class, 'resolveConflict'])
    ->name('language.resolve-conflict');

    
// Rutas públicas
Route::get('/', fn () => view('welcome'));

// Auth
require __DIR__.'/auth.php';

// 🔐 Rutas protegidas por login y verificación
Route::middleware(['auth', 'verified'])->group(function () {

    // 🏠 Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 👤 Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👮‍♂️ Rutas Admin (roles, permisos, usuarios)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware('can:users.index')->resource('users', AdminUserController::class);
        Route::middleware('can:roles.index')->resource('roles', RoleController::class);
        Route::middleware('can:permissions.index')->resource('permissions', PermissionController::class);
    });

    // ⚙️ Configuración del sistema (logo, idioma)
    Route::middleware('can:admin.access')->group(function () {
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/logo', [SettingsController::class, 'updateLogo'])->name('settings.updateLogo');
        Route::put('/settings/language', [SettingsController::class, 'updateLanguage'])->name('settings.updateLanguage');
    });

    Route::middleware('auth')->group(function () {
    // Nueva ruta para actualizar idioma de usuario
        Route::put('/profile/language', [ProfileController::class, 'updateLanguage'])
            ->name('profile.language.update');
});
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
       Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
       Route::delete('/feedback/{id}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
    });

    // 🗂 Logs Push relacionados con notificaciones
    Route::prefix('settings/push-logs')->name('push.logs.')->middleware('can:notifications.logs')->group(function () {
        Route::get('/', [PushLogController::class, 'index'])->name('');
        Route::get('/download/{filename}', [PushLogController::class, 'download'])->name('download');
        Route::delete('/delete/{filename}', [PushLogController::class, 'delete'])->name('delete');
    });

    // 📬 Notificaciones (CRUD completo + acciones)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index')->middleware('permission:notifications.view');
        Route::get('/create', [NotificationController::class, 'create'])->name('create')->middleware('permission:notifications.create');
        Route::post('/', [NotificationController::class, 'store'])->name('store')->middleware('permission:notifications.create');

        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show')->middleware('permission:notifications.view');
        Route::get('/{notification}/edit', [NotificationController::class, 'edit'])->name('edit')->middleware('permission:notifications.edit');
        Route::put('/{notification}', [NotificationController::class, 'update'])->name('update')->middleware('permission:notifications.edit');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy')->middleware('permission:notifications.delete');

        Route::post('/{notification}/publish', [NotificationController::class, 'publish'])->name('publish')->middleware('permission:notifications.publish');
        Route::post('/{notification}/send-push', [NotificationController::class, 'sendPush'])->name('send-push')->middleware('permission:notifications.publish');

        Route::post('/mark-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    
        // Estructura para las rutas de envío:
        Route::post('/{notification}/send-email', [NotificationController::class, 'sendEmail'])
            ->name('send-email')
            ->middleware('permission:notifications.publish');
        
        Route::post('/{notification}/send-web', [NotificationController::class, 'sendWeb'])
            ->name('send-web')
            ->middleware('permission:notifications.publish');
        
        Route::post('/{notification}/send-push', [NotificationController::class, 'sendPush'])
            ->name('send-push')
            ->middleware('permission:notifications.publish');
    });


    // ⚙️ API interna para frontend (no REST)
    Route::prefix('api')->group(function () {
        Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    });


    // 👥 Rutas específicas para gestores
    Route::middleware('role:gestor')->prefix('gestor')->name('gestor.')->group(function () {
        Route::get('/dashboard', [GestorController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', GestorUserController::class)->only(['index', 'edit', 'update']);
    });

    // Rutas administrativas
    Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
        // Event Types Routes
        Route::resource('event-types', EventTypeController::class)->except(['show']);
        
        // Events Routes
        Route::resource('events', EventController::class);
        Route::get('events-calendar', [EventController::class, 'calendar'])->name('events.calendar');
        Route::get('events-calendar/data', [EventController::class, 'calendarData'])->name('events.calendar-data');

        // Rutas para preguntas de eventos
        Route::resource('events.questions', EventQuestionController::class)->except(['show']);
        
        // Rutas para respuestas de eventos
        Route::resource('events.answers', EventAnswerController::class);
    });

    // Rutas públicas del calendario
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/event/{event}', [CalendarController::class, 'show'])->name('calendar.event.show');
    Route::get('/calendar/event/{event}/details', [CalendarController::class, 'eventDetails'])->name('calendar.event.details');
    Route::post('/calendar/event/answers', [CalendarController::class, 'saveAnswers'])->name('calendar.event.answers');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');


});
