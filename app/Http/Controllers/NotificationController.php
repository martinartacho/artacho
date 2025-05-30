<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Notification_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;


class NotificationController extends Controller
{
    public function __construct()
    {
        // Aplicar middleware de autenticación
        $this->middleware('auth');

        // Verificar permisos específicos para cada acción
        $this->middleware('permission:notifications.create')->only(['create', 'store']);
        $this->middleware('permission:notifications.edit')->only(['edit', 'update']);
        $this->middleware('permission:notifications.delete')->only('destroy');
        $this->middleware('permission:notifications.publish')->only('publish');

    }

    public function index()
    {
        // Usuarios con permiso pueden ver todas, los demás solo las propias o asignadas
        if (Auth::user()->hasRole(['admin', 'gestor', 'editor'])) {
            $notifications = Notification::latest()->paginate(10);
        } else {
            // Otros ven solo las suyas (relación muchos a muchos)
            $notifications = Auth::user()->notifications()->latest()->paginate(10);  
        }

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $recipientTypes = [
            'all' => __('site.All_users'),
            'role' =>  __('site.Users_role'),
            'specific' =>  __('site.Specific_users')
        ];
    
        // Obtener roles como array [id => nombre]
        $roles = \Spatie\Permission\Models\Role::all()->pluck('name', 'id');
        
        // Obtener todos los usuarios activos
        // $users = User::whereHas('roles')->get();
        // Obtener usuarios que NO tienen el rol 'invited'
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'invited');
        })->get();

    
        return view('notifications.create', [
            'recipientTypes' => $recipientTypes,
            'roles' => $roles,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,role,specific',
            'recipient_role' => 'nullable|required_if:recipient_type,role|exists:roles,name',
            'recipient_ids' => 'nullable|required_if:recipient_type,specific|array',
            'recipient_ids.*' => 'exists:users,id',
        ]);

        $user = Auth::user();
        $canPublish = $user->can('notifications.publish');

        $notification = Notification::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'sender_id' => $user->id,
            'recipient_type' => $validated['recipient_type'],
            'recipient_role' => $validated['recipient_type'] === 'role' ? $validated['recipient_role'] : null,
            'recipient_ids' => $validated['recipient_type'] === 'specific' ? $validated['recipient_ids'] : null,
            'is_published' => $canPublish,
            'published_at' => $canPublish ? now() : null,
        ]);

        if (method_exists($this, 'assignRecipients')) {
            $this->assignRecipients($notification);
        }

        return redirect()->route('notifications.index')->with('success', __('site.Notification_created'));
    }

    public function edit(Notification $notification)
    {
        // $this->authorize('update', $notification);
         $recipientTypes = [
        'all' => __('site.All_users'),
        'role' => __('site.Users_role'),
        'specific' => __('site.Specific_users')
        ];

        $roles = Role::all()->pluck('name', 'name');

        $users = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'invited');
        })->get();

        return view('notifications.edit', compact('notification', 'recipientTypes', 'roles', 'users'));
        
    }

    public function update(Request $request, Notification $notification)
    {
        $this->authorize('update-notification', $notification);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,role,specific',
            'recipient_role' => 'nullable|required_if:recipient_type,role|exists:roles,name',
            'recipient_ids' => 'nullable|required_if:recipient_type,specific|array',
            'recipient_ids.*' => 'exists:users,id',
        ]);

        $notification->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'recipient_type' => $validated['recipient_type'],
            'recipient_role' => $validated['recipient_type'] === 'role' ? $validated['recipient_role'] : null,
            'recipient_ids' => $validated['recipient_type'] === 'specific' ? $validated['recipient_ids'] : null,
        ]);

        // Actualizar destinatarios si aplica
        $notification->recipients()->detach();
        $this->assignRecipients($notification);

        return redirect()->route('notifications.index')
            ->with('success', __('site.Notification_updated'));
    }


    public function show(Notification $notification)
    {
        abort_if(Auth::user()->hasRole('invited'), 403);
        
        // Marcar como leída al visualizar
        if ($notification->read_at === null) {
            // Pasar el usuario autenticado como argumento
            $notification->markAsRead(Auth::user());
        }
        
        return view('notifications.show', compact('notification'));
    }


    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notificación eliminada');
    }


    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    // Publicar
    public function publish(Notification $notification)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'gestor', 'editor']) && 
            !Auth::user()->can('notifications.publish')) {
            abort(403);
        }

        $notification->update([
            'is_published' => true,
            'published_at' => now()
        ]);

        return redirect()->route('notifications.index')
            ->with('success', __('site.Notification_published'));
    }

    // marcar como leida
    public function markAsRead($user = null)
    {
        $user = $user ?: auth()->user();
        $this->read_at = now();
        $this->save();
        
        // O si usas relación muchos a muchos:
        $user->notifications()->updateExistingPivot($this->id, [
            'read_at' => now()
        ]);
    }


    // marcar todas como leidas
    public function markAllAsRead()
    {
        // Solución 1: Usar el método del trait Notifiable
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        // Solución 2: Alternativa si la anterior no funciona
        // foreach (Auth::user()->unreadNotifications as $notification) {
        //     $notification->markAsRead();
        // }
        
        return response()->json(['success' => true]);
    }

    

    protected function assignRecipients(Notification $notification)
    {
        if ($notification->recipient_type === 'all') {
            $users = User::whereDoesntHave('roles', function($q) {
                $q->where('name', 'invited');
            })->pluck('id');
        } elseif ($notification->recipient_type === 'role') {
            $users = User::role($notification->recipient_role)->pluck('id');
        } elseif ($notification->recipient_type === 'specific') {
            $users = collect($notification->recipient_ids);
        } else {
            $users = collect(); // fallback
        }

        $notification->recipients()->sync($users);
    }

}
