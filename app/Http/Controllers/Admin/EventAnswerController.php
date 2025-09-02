<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAnswer;
use Illuminate\Http\Request;

class EventAnswerController extends Controller
{
    /**
     * Display a listing of the answers for an event.
     */
    public function index(Event $event)
    {
        $this->authorize('viewAny', EventAnswer::class);
        
        $answers = $event->answers()->with(['user', 'question'])->get();
        return view('admin.events.answers.index', compact('event', 'answers'));
    }

    /**
     * Show the form for creating a new answer.
     */
    public function create(Event $event)
    {
        $this->authorize('create', EventAnswer::class);
        
        $questions = $event->questions;
        return view('admin.events.answers.create', compact('event', 'questions'));
    }

    /**
     * Store a newly created answer.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('create', EventAnswer::class);
        
        $validated = $request->validate([
            'question_id' => 'required|exists:event_questions,id',
            'answer' => 'required|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['event_id'] = $event->id;

        EventAnswer::create($validated);

        return redirect()->route('admin.events.answers.index', $event)
            ->with('success', __('site.Answer created successfully.'));
    }

    /**
     * Display the specified answer.
     */
    public function show(Event $event, EventAnswer $answer)
    {
        $this->authorize('view', $answer);
        
        return view('admin.events.answers.show', compact('event', 'answer'));
    }

    /**
     * Remove the specified answer.
     */
    public function destroy(Event $event, EventAnswer $answer)
    {
        $this->authorize('delete', $answer);
        
        $answer->delete();

        return redirect()->route('admin.events.answers.index', $event)
            ->with('success', __('site.Answer deleted successfully.'));
    }
}