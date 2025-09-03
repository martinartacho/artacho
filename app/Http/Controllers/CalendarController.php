<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Mostrar el calendario público
     */
    public function index()
    {
        $eventTypes = EventType::all();
        return view('calendar.index', compact('eventTypes'));
    }

    /**
     * Mostrar un evento específico
     */
    public function show(Event $event)
    {
        // Verificar que el evento es visible
        if (!$event->visible || 
            ($event->start_visible && $event->start_visible > now()) ||
            ($event->end_visible && $event->end_visible < now())) {
            abort(404);
        }
      //   dd('Prepara vista show',  $event);
        return view('calendar.show', compact('event'));
    }

    /**
     * Obtener eventos para el calendario (JSON)
     */
    public function events(Request $request)
    {
        $query = Event::where('visible', true)
            ->where(function($query) {
                $query->whereNull('start_visible')
                    ->orWhere('start_visible', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_visible')
                    ->orWhere('end_visible', '>=', now());
            });

        // Filtrar por tipo de evento si se especifica
        if ($request->has('event_type_id')) {
            $query->where('event_type_id', $request->event_type_id);
        }

        // Filtrar por rango de fechas si se especifica
        if ($request->has('start') && $request->has('end')) {
            $query->where(function($q) use ($request) {
                $q->whereBetween('start', [$request->start, $request->end])
                  ->orWhereBetween('end', [$request->start, $request->end])
                  ->orWhere(function($q) use ($request) {
                      $q->where('start', '<=', $request->start)
                        ->where('end', '>=', $request->end);
                  });
            });
        }

        $events = $query->get();

        return response()->json($events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'color' => $event->color ?? ($event->eventType->color ?? '#3c8dbc'),
                'url' => route('calendar.event.show', $event->id),
                'extendedProps' => [
                    'description' => $event->description,
                    'event_type' => $event->eventType->name ?? 'None',
                    'max_users' => $event->max_users,
                ]
            ];
        }));
    }

    /**
     * Mostrar eventos próximos (para el dashboard)
     */
    public function upcomingEvents($limit = 5)
    {
        $events = Event::where('visible', true)
            ->where(function($query) {
                $query->whereNull('start_visible')
                    ->orWhere('start_visible', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_visible')
                    ->orWhere('end_visible', '>=', now());
            })
            ->where('start', '>=', now())
            ->orderBy('start', 'asc')
            ->limit($limit)
            ->get();

        return $events;
    }
}