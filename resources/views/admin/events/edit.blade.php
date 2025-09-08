<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('site.Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(request()->is('admin/events/create'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                    <p class="font-bold">{{ __('Information') }}</p>
                    <p>{{ __('To create recurring events, select a recurrence type other than "None".') }}</p>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }} *</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="event_type_id" class="block text-sm font-medium text-gray-700">{{ __('Event Type') }}</label>
                                <select name="event_type_id" id="event_type_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">{{ __('Select an event type') }}</option>
                                    @foreach($eventTypes as $type)
                                        <option value="{{ $type->id }}" {{ (old('event_type_id', $event->event_type_id) == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('event_type_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="start" class="block text-sm font-medium text-gray-700">{{ __('Start Date/Time') }} *</label>
                                <input type="datetime-local" name="start" id="start" value="{{ old('start', $event->start->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end" class="block text-sm font-medium text-gray-700">{{ __('End Date/Time') }}</label>
                                <input type="datetime-local" name="end" id="end" value="{{ old('end', $event->end ? $event->end->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">{{ __('Color') }}</label>
                                <input type="color" name="color" id="color" value="{{ old('color', $event->color ?? '#3c8dbc') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-10">
                                @error('color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="max_users" class="block text-sm font-medium text-gray-700">{{ __('Maximum Users') }}</label>
                                <input type="number" name="max_users" id="max_users" value="{{ old('max_users', $event->max_users) }}" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('max_users')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="visible" id="visible" value="1" {{ old('visible', $event->visible) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="visible" class="ml-2 block text-sm text-gray-900">{{ __('Visible') }}</label>
                                @error('visible')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="start_visible" class="block text-sm font-medium text-gray-700">{{ __('Visible From') }}</label>
                                <input type="datetime-local" name="start_visible" id="start_visible" value="{{ old('start_visible', $event->start_visible ? $event->start_visible->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('start_visible')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_visible" class="block text-sm font-medium text-gray-700">{{ __('Visible Until') }}</label>
                                <input type="datetime-local" name="end_visible" id="end_visible" value="{{ old('end_visible', $event->end_visible ? $event->end_visible->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('end_visible')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                                                        <div class="md:col-span-2 border-t pt-4 mt-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Question Template') }}</h4>
                                <div class="flex items-center space-x-2">
                                    <select name="question_template_id" id="question-template-selector" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">{{ __('site.Select a template') }}</option>
                                        @foreach($questionTemplates as $template)
                                            <option value="{{ $template->id }}">{{ $template->template_name }} ({{ $template->type }})</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="load-template-questions-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('site.Load Questions') }}
                                    </button>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">{{ __('Selecting a template will add these questions to all recurring events.') }}</p>
                            </div>
                            <div id="questions-container" class="md:col-span-2">
                            </div>


                            <div class="md:col-span-2 border-t pt-4 mt-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recurrence Settings') }}</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="recurrence_type" class="block text-sm font-medium text-gray-700">{{ __('Recurrence Type') }}</label>
                                        <select name="recurrence_type" id="recurrence_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="none" {{ old('recurrence_type', isset($event) ? $event->recurrence_type : 'none') == 'none' ? 'selected' : '' }}>{{ __('None') }}</option>
                                            <option value="daily" {{ old('recurrence_type', isset($event) ? $event->recurrence_type : 'none') == 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                                            <option value="weekly" {{ old('recurrence_type', isset($event) ? $event->recurrence_type : 'none') == 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                                            <option value="monthly" {{ old('recurrence_type', isset($event) ? $event->recurrence_type : 'none') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                                            <option value="yearly" {{ old('recurrence_type', isset($event) ? $event->recurrence_type : 'none') == 'yearly' ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="recurrence_interval" class="block text-sm font-medium text-gray-700">{{ __('Repeat Every') }}</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="number" name="recurrence_interval" id="recurrence_interval" min="1" value="{{ old('recurrence_interval', isset($event) ? $event->recurrence_interval : 1) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-20 rounded-none rounded-l-md sm:text-sm border-gray-300">
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm" id="recurrence_interval_label">
                                            {{ __('site.days') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                                        <input type="date" name="recurrence_end_date" id="recurrence_end_date" value="{{ old('recurrence_end_date', isset($event) && $event->recurrence_end_date ? $event->recurrence_end_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="recurrence_count" class="block text-sm font-medium text-gray-700">{{ __('Number of Occurrences') }}</label>
                                        <input type="number" name="recurrence_count" id="recurrence_count" min="1" value="{{ old('recurrence_count', isset($event) ? $event->recurrence_count : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.events.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                               {{ __('site.Cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                               {{ __('site.Update Event') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para la recurrencia
        const recurrenceType = document.getElementById('recurrence_type');
        const intervalLabel = document.getElementById('recurrence_interval_label');
        const endDateField = document.getElementById('recurrence_end_date').closest('div');
        const countField = document.getElementById('recurrence_count').closest('div');
        
        function updateRecurrenceFields() {
            const type = recurrenceType.value;
            let label = '';
            
            switch(type) {
                case 'daily':
                    label = '{{ __("site.days") }}';
                    break;
                case 'weekly':
                    label = '{{ __("site.weeks") }}';
                    break;
                case 'monthly':
                    label = '{{ __("site.months") }}';
                    break;
                case 'yearly':
                    label = '{{ __("site.years") }}';
                    break;
                default:
                    label = '{{ __("site.days") }}';
            }
            
            intervalLabel.textContent = label;
            
            if (type === 'none') {
                endDateField.style.display = 'none';
                countField.style.display = 'none';
            } else {
                endDateField.style.display = 'block';
                countField.style.display = 'block';
            }
        }
        
        recurrenceType.addEventListener('change', updateRecurrenceFields);
        updateRecurrenceFields(); // Llamada inicial

        // --- Lógica para las plantillas de preguntas (NUEVO CÓDIGO) ---
        const loadTemplateBtn = document.getElementById('load-template-questions-btn');
        const templateSelector = document.getElementById('question-template-selector');
        const questionsContainer = document.getElementById('questions-container'); // Se crea en el paso 2

        if (loadTemplateBtn) {
            loadTemplateBtn.addEventListener('click', function() {
                console.info('Dendro de loadTemplateBtn');

                const templateId = templateSelector.value;
                if (!templateId) {
                    alert('Por favor, selecciona una plantilla.');
                    return;
                }

                // Realiza la petición AJAX al servidor
                fetch(`/admin/question-templates/${templateId}/questions`) // Asegúrate de que esta URL sea correcta
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('No se pudo cargar la plantilla.');
                        }
                        return response.json();
                    })
                    .then(questions => {
                        // Limpia el contenedor de preguntas existente
                        if (questionsContainer) {
                            questionsContainer.innerHTML = '';
                            
                            // Construye el HTML para cada pregunta
                            questions.forEach((question, index) => {
                                const questionHtml = `
                                    <div class="mb-4 p-4 border rounded-md bg-gray-50">
                                        <div class="mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Pregunta ${index + 1}:</label>
                                            <input type="text" name="questions[${index}][question_text]" value="${question.question_text}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tipo:</label>
                                            <input type="text" value="${question.question_type}" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed">
                                        </div>
                                        <div class="mt-2">
                                            <label class="block text-sm font-medium text-gray-700">Opciones (separadas por coma):</label>
                                            <input type="text" name="questions[${index}][options]" value="${question.options.join(', ')}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <input type="hidden" name="questions[${index}][question_type]" value="${question.question_type}">
                                    </div>
                                `;
                                questionsContainer.innerHTML += questionHtml;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar la plantilla:', error);
                        alert('Hubo un error al cargar la plantilla. Por favor, inténtalo de nuevo.');
                    });
            });
        }
    });
</script>
@endpush
</x-app-layout>