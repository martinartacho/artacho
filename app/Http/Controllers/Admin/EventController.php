<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Event::class);
        
        $events = Event::with('eventType')->paginate(10); // 10 elementos por página
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
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', __('Event created successfully.'));
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
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', __('Event updated successfully.'));
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', __('Event deleted successfully.'));
    }
    
    public function calendar()
    {
        $this->authorize('viewAny', Event::class);
        
        return view('admin.events.calendar');
    }
    
    public function calendarData()
    {
        $this->authorize('viewAny', Event::class);
        
        $events = Event::where('visible', true)
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
                'start' => $event->start,
                'end' => $event->end,
                'color' => $event->color ?? ($event->eventType->color ?? '#3c8dbc'),
                'url' => route('admin.events.edit', $event->id),
            ];
        }));
    }
}