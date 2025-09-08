<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\EventQuestion;
use App\Models\EventAnswer;
use App\Models\EventQuestionTemplate;
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
        $questionTemplates = EventQuestionTemplate::templates()->get(); 
        return view('admin.events.create', compact('eventTypes', 'questionTemplates'));

    }
 
/*
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
                    
                    // Añadir una validación para la plantilla de preguntas si es un campo que pasas
                    'question_template_id' => 'nullable|exists:event_question_templates,id', 
                    
                ]);

        $validated['visible'] = (bool)($validated['visible'] ?? false);
        
        try {
            // Crear el evento principal primero, siempre
            $event = Event::create($validated);
            
            // Si hay una plantilla seleccionada, aplicarla al evento principal
            if ($request->filled('question_template_id')) {
                $template = EventQuestionTemplate::find($request->input('question_template_id'));
                if ($template) {
                    $event->questions()->create([
                        'question' => $template->question,
                        'type' => $template->type,
                        'options' => $template->options,
                        'required' => $template->required,
                    ]);
                }
            }
            
            // Si es un evento recurrente, crear los hijos
            if ($validated['recurrence_type'] !== 'none') {
                // Llama a la función pasando el ID del evento que acabamos de crear
                $createdCount = $this->createRecurringEvents($validated, $event->id);
                return redirect()->route('admin.events.index')
                    ->with('success', __('site.Created :count recurring events.', ['count' => $createdCount]));
            } else {
                return redirect()->route('admin.events.index')
                    ->with('success', __('site.Event created successfully.'));
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('site.Error creating event: :message', ['message' => $e->getMessage()]));
        }
    }
*/


public function store(Request $request)
{
    // Validación de los datos
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'event_type_id' => 'nullable|exists:event_types,id',
        'start' => 'required|date',
        'end' => 'nullable|date|after_or_equal:start',
        'color' => 'nullable|string',
        'max_users' => 'nullable|integer|min:1',
        'visible' => 'boolean',
        'start_visible' => 'nullable|date',
        'end_visible' => 'nullable|date|after_or_equal:start_visible',
        'description' => 'nullable|string',
        'recurrence_type' => 'required|in:none,daily,weekly,monthly,yearly',
        'recurrence_interval' => 'nullable|integer|min:1',
        'recurrence_end_date' => 'nullable|date',
        'recurrence_count' => 'nullable|integer|min:1',
    ]);

    // Lógica para eventos recurrentes
    if ($validatedData['recurrence_type'] !== 'none') {
        // Crear el evento padre
        $parentEvent = Event::create([
            'title' => $validatedData['title'],
            'event_type_id' => $validatedData['event_type_id'],
            'start' => $validatedData['start'],
            'end' => $validatedData['end'],
            'color' => $validatedData['color'],
            'max_users' => $validatedData['max_users'],
            'visible' => $validatedData['visible'] ?? false,
            'start_visible' => $validatedData['start_visible'],
            'end_visible' => $validatedData['end_visible'],
            'description' => $validatedData['description'],
            'recurrence_type' => $validatedData['recurrence_type'],
            'recurrence_interval' => $validatedData['recurrence_interval'] ?? 1,
            'recurrence_end_date' => $validatedData['recurrence_end_date'],
            'recurrence_count' => $validatedData['recurrence_count'],
        ]);

        $parentEvent->parent_id = $parentEvent->id; // El padre se referencia a sí mismo
        $parentEvent->save();

        // Generar los eventos hijos
        $start = Carbon::parse($validatedData['start']);
        $end = $validatedData['end'] ? Carbon::parse($validatedData['end']) : null;
        
        $duration = $end ? $start->diffInMinutes($end) : null;
        $count = $validatedData['recurrence_count'] ?? PHP_INT_MAX;
        $interval = $validatedData['recurrence_interval'] ?? 1;
        $endDate = $validatedData['recurrence_end_date'] ? Carbon::parse($validatedData['recurrence_end_date'])->endOfDay() : null;

        for ($i = 1; $i < $count; $i++) {
            $newStart = clone $start;
            $newEnd = $end ? clone $end : null;

            switch ($validatedData['recurrence_type']) {
                case 'daily':
                    $newStart->addDays($interval * $i);
                    if ($newEnd) $newEnd->addDays($interval * $i);
                    break;
                case 'weekly':
                    $newStart->addWeeks($interval * $i);
                    if ($newEnd) $newEnd->addWeeks($interval * $i);
                    break;
                case 'monthly':
                    $newStart->addMonths($interval * $i);
                    if ($newEnd) $newEnd->addMonths($interval * $i);
                    break;
                case 'yearly':
                    $newStart->addYears($interval * $i);
                    if ($newEnd) $newEnd->addYears($interval * $i);
                    break;
            }

            if ($endDate && $newStart->gt($endDate)) {
                break;
            }

            Event::create([
                'title' => $validatedData['title'],
                'event_type_id' => $validatedData['event_type_id'],
                'start' => $newStart,
                'end' => $newEnd,
                'color' => $validatedData['color'],
                'max_users' => $validatedData['max_users'],
                'visible' => $validatedData['visible'] ?? false,
                'start_visible' => $validatedData['start_visible'],
                'end_visible' => $validatedData['end_visible'],
                'description' => $validatedData['description'],
                'parent_id' => $parentEvent->id, // Aquí asignamos el ID del padre
                'recurrence_type' => $validatedData['recurrence_type'],
                'recurrence_interval' => $validatedData['recurrence_interval'] ?? 1,
                'recurrence_end_date' => $validatedData['recurrence_end_date'],
                'recurrence_count' => $validatedData['recurrence_count'],
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Eventos recurrentes creados con éxito.');
    } else {
        // Lógica para un evento simple
        Event::create($validatedData);
        return redirect()->route('admin.events.index')->with('success', 'Evento creado con éxito.');
    }
}

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        $eventTypes = EventType::all();
        $questionTemplates = EventQuestionTemplate::templates()->get(); 

        return view('admin.events.edit', compact('event', 'eventTypes', 'questionTemplates'));
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

    private function createRecurringEvents(array $eventData, int $parentId)
    {
        $createdCount = 0;
        
        // Obtener el evento padre con sus preguntas
        $parentEvent = Event::with('questions')->find($parentId);
        
        // Si no existe el padre, no podemos continuar
        if (!$parentEvent) {
            throw new \Exception('Parent event not found to create recurring events.');
        }

        try {
            $start = Carbon::parse($eventData['start']);
            $end = isset($eventData['end']) ? Carbon::parse($eventData['end']) : null;
            $interval = (int) $eventData['recurrence_interval'];
            $type = $eventData['recurrence_type'];
            $endDate = isset($eventData['recurrence_end_date']) ? Carbon::parse($eventData['recurrence_end_date']) : null;
            $count = isset($eventData['recurrence_count']) ? (int) $eventData['recurrence_count'] : null;
            
            $duration = $end ? $end->diffInSeconds($start) : 0;
            
            while (true) {
                $eventEnd = $end ? $start->copy()->addSeconds($duration) : null;
                
                // Datos del nuevo evento recurrente
                $newEventData = array_merge($eventData, [
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $eventEnd ? $eventEnd->format('Y-m-d H:i:s') : null,
                    'recurrence_type' => 'none', // Los hijos son eventos individuales
                    'parent_id' => $parentId, // Asignar el ID del padre
                ]);

                // Crear el nuevo evento en la base de datos
                $newEvent = Event::create($newEventData);
                
                // Copiar las preguntas del evento padre al nuevo evento hijo
                foreach ($parentEvent->questions as $question) {
                    $newEvent->questions()->create([
                        'question' => $question->question,
                        'type' => $question->type,
                        'options' => $question->options,
                        'required' => $question->required,
                    ]);
                }
                
                $createdCount++;
                
                // Lógica para avanzar la fecha y las condiciones de salida
                if (($count && $createdCount >= $count) || ($endDate && $start->gt($endDate))) {
                    break;
                }
                
                switch ($type) {
                    case 'daily': $start->addDays($interval); break;
                    case 'weekly': $start->addWeeks($interval); break;
                    case 'monthly': $start->addMonths($interval); break;
                    case 'yearly': $start->addYears($interval); break;
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error creating recurring events: ' . $e->getMessage());
            throw $e; // Re-lanza la excepción para que el store la capture
        }

        return $createdCount;
    }

    /*
    private function createRecurringEvents(array $eventData)
    {
        $createdCount = 0;try {
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
                foreach ($eventsData as $eventData) {
                    $newEvent = Event::create($eventData + ['parent_id' => $parentId]);
                    
                    // Copiar las preguntas del padre al nuevo evento hijo
                    $parentEvent = Event::find($parentId);
                    if ($parentEvent) {
                        foreach ($parentEvent->questions as $question) {
                            $newEvent->questions()->create([
                                'question' => $question->question,
                                'type' => $question->type,
                                'options' => $question->options,
                                'required' => $question->required,
                            ]);
                        }
                    }
                    $createdCount++;
                }
                return $createdCount;
            }
            
            return count($events);
            
        } catch (\Exception $e) {
            Log::error('Error creating recurring events: ' . $e->getMessage());
            throw $e;
        }
    } */


}