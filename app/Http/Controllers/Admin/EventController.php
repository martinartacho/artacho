<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Event::class);
        
        $events = Event::with('eventType')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $this->authorize('create', Event::class);
        
        $eventTypes = EventType::all();
        return view('admin.events.create', compact('eventTypes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Event::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'color' => 'nullable|string|max:255',
            'max_users' => 'nullable|integer|min:1',
            'visible' => 'boolean',
            'start_visible' => 'nullable|date',
            'end_visible' => 'nullable|date|after:start_visible',
            'event_type_id' => 'nullable|exists:event_types,id',
            'recurrence_type' => 'required|in:none,daily,weekly,monthly,yearly',
            'recurrence_interval' => 'required|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:start',
            'recurrence_count' => 'nullable|integer|min:0', 
        ]);

        $validated['visible'] = (bool)($validated['visible'] ?? false);
        
        try {
            if ($validated['recurrence_type'] !== 'none') {
                $createdCount = $this->createRecurringEvents($validated);
                return redirect()->route('admin.events.index')
                    ->with('success', __('site.Created :count recurring events.', ['count' => $createdCount]));
            } else {
                Event::create($validated);
                return redirect()->route('admin.events.index')
                    ->with('success', __('site.Event created successfully.'));
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('site.Error creating event: :message', ['message' => $e->getMessage()]));
        }
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        $eventTypes = EventType::all();
        return view('admin.events.edit', compact('event', 'eventTypes'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'color' => 'nullable|string|max:255',
            'max_users' => 'nullable|integer|min:1',
            'visible' => 'boolean',
            'start_visible' => 'nullable|date',
            'end_visible' => 'nullable|date|after:start_visible',
            'event_type_id' => 'nullable|exists:event_types,id',
            'recurrence_type' => 'required|in:none,daily,weekly,monthly,yearly',
            'recurrence_interval' => 'required|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:start',
            'recurrence_count' => 'nullable|integer|min:0', 
        ]);

        $validated['visible'] = (bool)($validated['visible'] ?? false);

        try {
            $event->update($validated);
            return redirect()->route('admin.events.index')
                ->with('success', __('site.Event updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('site.Error updating event: :message', ['message' => $e->getMessage()]));
        }
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        try {
            $event->delete();
            return redirect()->route('admin.events.index')
                ->with('success', __('site.Event deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('site.Error deleting event: :message', ['message' => $e->getMessage()]));
        }
    }
    
    public function calendar()
    {
        $this->authorize('viewAny', Event::class);
        
        return view('admin.events.calendar');
    }
    
    
    public function calendarData()
    {
        try {
            $this->authorize('viewAny', Event::class);
            
            $events = Event::with('eventType')
                ->where('visible', true)
                ->where(function($query) {
                    $query->whereNull('start_visible')
                        ->orWhere('start_visible', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('end_visible')
                        ->orWhere('end_visible', '>=', now());
                })
                ->get();
                
            return response()->json($events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start->toIso8601String(),
                    'end' => $event->end ? $event->end->toIso8601String() : null,
                    'color' => $event->color ?? ($event->eventType->color ?? '#3c8dbc'),
                    'url' => route('admin.events.edit', $event->id),
                    'allDay' => !$event->end || $event->start->isSameDay($event->end),
                ];
            }));
        } catch (\Exception $e) {
            Log::error('Error fetching calendar data: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    private function createRecurringEvents(array $eventData)
    {
        try {
            $start = Carbon::parse($eventData['start']);
            $end = isset($eventData['end']) ? Carbon::parse($eventData['end']) : null;
            
            // Asegurarnos de que los valores numéricos sean integers
            $interval = (int) $eventData['recurrence_interval'];
            $type = $eventData['recurrence_type'];
            $endDate = isset($eventData['recurrence_end_date']) ? Carbon::parse($eventData['recurrence_end_date']) : null;
            $count = isset($eventData['recurrence_count']) ? (int) $eventData['recurrence_count'] : null;
            
            $events = [];
            $currentCount = 0;
            
            // Calcular la diferencia entre start y end para mantenerla en cada evento
            $duration = $end ? $end->diffInSeconds($start) : 0;
            
            while (true) {
                $eventEnd = $end ? $start->copy()->addSeconds($duration) : null;
                
                // Crear evento actual
                $events[] = array_merge($eventData, [
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $eventEnd ? $eventEnd->format('Y-m-d H:i:s') : null,
                    'recurrence_type' => 'none', // Los eventos individuales no son recurrentes
                ]);
                
                $currentCount++;
                
                // Verificar condiciones de salida
                if (($count && $currentCount >= $count) || 
                    ($endDate && $start->gt($endDate))) {
                    break;
                }
                
                // Avanzar la fecha según el tipo de recurrencia
                // Usar (int) para asegurar que siempre sea un número entero
                switch ($type) {
                    case 'daily':
                        $start->addDays((int) $interval);
                        break;
                    case 'weekly':
                        $start->addWeeks((int) $interval);
                        break;
                    case 'monthly':
                        $start->addMonths((int) $interval);
                        break;
                    case 'yearly':
                        $start->addYears((int) $interval);
                        break;
                }
            }
            
            // Insertar todos los eventos
            foreach ($events as $event) {
                Event::create($event);
            }
            
            return count($events);
            
        } catch (\Exception $e) {
            Log::error('Error creating recurring events: ' . $e->getMessage());
            throw $e;
        }
    }

}