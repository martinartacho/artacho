<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventQuestionTemplate;
use Illuminate\Http\Request;

class EventQuestionTemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        $this->authorize('viewAny', EventQuestionTemplate::class);
        
        $templates = EventQuestionTemplate::templates()
            ->orderBy('template_name')
            ->paginate(10);
            
        return view('admin.event-question-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        $this->authorize('create', EventQuestionTemplate::class);
        
        return view('admin.event-question-templates.create');
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $this->authorize('create', EventQuestionTemplate::class);
        
        $validated = $request->validate([
            'template_name' => 'required|string|max:255',
            'template_description' => 'nullable|string',
            'question' => 'required|string|max:1000',
            'type' => 'required|in:text,single,multiple',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'required' => 'boolean',
        ]);

        // Marcar como plantilla y guardar
        $validated['is_template'] = true;
        
        // Filtrar opciones vacías
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($option) {
                return !empty(trim($option));
            });
            
            if (empty($validated['options'])) {
                $validated['options'] = null;
            }
        }

        EventQuestionTemplate::create($validated);

        return redirect()->route('admin.event-question-templates.index')
            ->with('success', __('site.Template created successfully.'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(EventQuestionTemplate $template)
    {
        $this->authorize('update', $template);
        
        return view('admin.event-question-templates.edit', compact('template'));
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, EventQuestionTemplate $template)
    {
        $this->authorize('update', $template);
        
        $validated = $request->validate([
            'template_name' => 'required|string|max:255',
            'template_description' => 'nullable|string',
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
            
            if (empty($validated['options'])) {
                $validated['options'] = null;
            }
        }

        $template->update($validated);

        return redirect()->route('admin.event-question-templates.index')
            ->with('success', __('site.Template updated successfully.'));
    }

    /**
     * Remove the specified template.
     */
    public function destroy(EventQuestionTemplate $template)
    {
        $this->authorize('delete', $template);
        
        $template->delete();

        return redirect()->route('admin.event-question-templates.index')
            ->with('success', __('site.Template deleted successfully.'));
    }

    /**
     * API endpoint para obtener plantillas (usado en AJAX)
     */
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', EventQuestionTemplate::class);
        
        $templates = EventQuestionTemplate::templates()
            ->orderBy('template_name')
            ->get();
            
        return response()->json($templates);
    }

     public function getQuestions($templateId)
    {
        $template = QuestionTemplate::with('questions')->findOrFail($templateId);

        // Devolvemos las preguntas de la plantilla como JSON
        return response()->json($template->questions);
    }
    
    
}