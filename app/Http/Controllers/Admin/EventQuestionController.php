<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventQuestionTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventQuestionController extends Controller
{
    /**
     * Display a listing of the questions for an event.
     */
    public function index(Event $event)
    {
        $this->authorize('viewAny', EventQuestion::class);
        
        $questions = $event->questions;

        return view('admin.events.questions.index', compact('event', 'questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Event $event)
    {
        $this->authorize('create', EventQuestion::class);
        
        $templates = EventQuestionTemplate::where('is_template', true)->get();
        
        return view('admin.events.questions.create', compact('event', 'templates'));
    }
    /**
     * Store a newly created question.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('create', EventQuestion::class);
        
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'type' => 'required|in:text,single,multiple',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'required' => 'boolean',
        ]);

        // Filtrar opciones vacías
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($option) {
                return !empty(trim($option));
            });
            
            // Si no hay opciones después de filtrar, establecer a null
            if (empty($validated['options'])) {
                $validated['options'] = null;
            }
        }

        $event->questions()->create($validated);

        return redirect()->route('admin.events.questions.index', $event)
            ->with('success', __('site.Question created successfully.'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, Event $event, EventQuestion $question)
    {
        $this->authorize('update', $question);
        
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'type' => 'required|in:text,single,multiple',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'required' => 'boolean',
        ]);

        // Filtrar opciones vacías
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($option) {
                return !empty(trim($option));
            });
            
            // Si no hay opciones después de filtrar, establecer a null
            if (empty($validated['options'])) {
                $validated['options'] = null;
            }
        }

        $question->update($validated);

        return redirect()->route('admin.events.questions.index', $event)
            ->with('success', __('site.Question updated successfully.'));
    }


    /**
     * Show the form for editing the specified question.
     */
    public function edit(Event $event, EventQuestion $question)
    {
        $this->authorize('update', $question);
        
        $templates = EventQuestionTemplate::where('is_template', true)->get();
        
        return view('admin.events.questions.edit', compact('event', 'question', 'templates'));
    }

    /**
     * Remove the specified question.
     */
    public function destroy(Event $event, EventQuestion $question)
    {
        $this->authorize('delete', $question);
        
        $question->delete();

        return redirect()->route('admin.events.questions.index', $event)
            ->with('success', __('site.Question deleted successfully.'));
    }


}