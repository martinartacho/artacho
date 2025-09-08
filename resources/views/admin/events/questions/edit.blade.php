<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.Edit Question for Event') }}: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Añadir después del título y antes del formulario --}}
<div class="mb-6 bg-blue-50 p-4 rounded-lg">
    <h4 class="text-lg font-medium text-blue-800 mb-2">{{ __('site.Load from Template') }}</h4>
    <div class="flex items-center space-x-2">
        <select id="template-selector" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">{{ __('site.Select a template') }}</option>
            @foreach($templates as $template)
                <option value="{{ $template->id }}" 
                    data-question="{{ $template->question }}"
                    data-type="{{ $template->type }}"
                    data-options="{{ $template->options ? json_encode($template->options) : '[]' }}"
                    data-required="{{ $template->required ? '1' : '0' }}">
                    {{ $template->template_name }} ({{ $template->type }})
                </option>
            @endforeach
        </select>
        <button type="button" id="load-template-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ __('site.Load Template') }}
        </button>
    </div>
</div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.events.questions.update', [$event, $question]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700">
                                {{ __('site.Question') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="question" id="question" value="{{ old('question', $question->question) }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('question')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">
                                {{ __('site.Type') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="text" {{ old('type', $question->type) == 'text' ? 'selected' : '' }}>{{ __('site.Text') }}</option>
                                <option value="single" {{ old('type', $question->type) == 'single' ? 'selected' : '' }}>{{ __('site.Single Choice') }}</option>
                                <option value="multiple" {{ old('type', $question->type) == 'multiple' ? 'selected' : '' }}>{{ __('site.Multiple Choice') }}</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4" id="options-container" style="{{ in_array(old('type', $question->type), ['single', 'multiple']) ? 'block' : 'none' }};">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('site.Options') }} <span class="text-red-500">*</span>
                            </label>
                            <p class="text-sm text-gray-500 mb-2">{{ __('site.Add at least one option for choice questions') }}</p>
                            <div id="options-list" class="space-y-2 mb-2">
                                <!-- Las opciones se añadirán dinámicamente -->
                            </div>
                            <button type="button" id="add-option" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('site.Add Option') }}
                            </button>
                            @error('options')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('options.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="required" id="required" value="1" {{ old('required', $question->required) ? 'checked' : '' }}
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="required" class="ml-2 block text-sm text-gray-900">{{ __('site.Required') }}</label>
                            </div>
                            @error('required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.events.questions.index', $event) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                {{ __('site.Cancel') }}
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('site.Update') }}
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
        // --- Gestión de Recurrencia (para eventos) ---
        const recurrenceType = document.getElementById('recurrence_type');
        if (recurrenceType) {
            const intervalLabel = document.getElementById('recurrence_interval_label');
            const endDateField = document.getElementById('recurrence_end_date').closest('div');
            const countField = document.getElementById('recurrence_count').closest('div');
            
            function updateRecurrenceFields() {
                const type = recurrenceType.value;
                let label = '';
                
                // Actualizar etiqueta del intervalo
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
                
                if (intervalLabel) intervalLabel.textContent = label;
                
                // Mostrar/ocultar campos según el tipo de recurrencia
                if (type === 'none') {
                    if (endDateField) endDateField.style.display = 'none';
                    if (countField) countField.style.display = 'none';
                } else {
                    if (endDateField) endDateField.style.display = 'block';
                    if (countField) countField.style.display = 'block';
                }
            }
            
            recurrenceType.addEventListener('change', updateRecurrenceFields);
            updateRecurrenceFields(); // Initial call
        }
        
        // --- Gestión de Plantillas (para preguntas) ---
        const templateSelector = document.getElementById('template-selector');
        const loadTemplateBtn = document.getElementById('load-template-btn');
        
        if (templateSelector && loadTemplateBtn) {
            loadTemplateBtn.addEventListener('click', function() {
                const selectedTemplate = templateSelector.options[templateSelector.selectedIndex];
                
                if (selectedTemplate.value) {
                    // Cargar datos de la plantilla en el formulario
                    const questionField = document.getElementById('question');
                    const typeField = document.getElementById('type');
                    const requiredField = document.getElementById('required');
                    const optionsList = document.getElementById('options-list');
                    
                    if (questionField) questionField.value = selectedTemplate.dataset.question || '';
                    if (typeField) typeField.value = selectedTemplate.dataset.type || 'text';
                    
                    // Cargar opciones si es una pregunta de selección
                    if (optionsList && selectedTemplate.dataset.options) {
                        try {
                            const options = JSON.parse(selectedTemplate.dataset.options);
                            optionsList.innerHTML = ''; // Limpiar opciones existentes
                            
                            options.forEach(function(option) {
                                addOption(option);
                            });
                        } catch (e) {
                            console.error('Error parsing options:', e);
                        }
                    }
                    
                    // Establecer campo requerido
                    if (requiredField) {
                        requiredField.checked = selectedTemplate.dataset.required === '1';
                    }
                    
                    // Disparar evento change para actualizar la UI
                    if (typeField) {
                        const event = new Event('change');
                        typeField.dispatchEvent(event);
                    }
                    
                    // Mostrar mensaje de éxito
                    alert('Plantilla cargada correctamente');
                } else {
                    alert('Por favor, selecciona una plantilla');
                }
            });
        }
        
        // --- Funciones para gestión de opciones ---
        function addOption(value = '') {
            const optionsList = document.getElementById('options-list');
            if (!optionsList) return;
            
            const optionIndex = optionsList.children.length;
            const optionDiv = document.createElement('div');
            optionDiv.className = 'flex items-center mb-2';
            optionDiv.innerHTML = `
                <input type="text" name="options[${optionIndex}]" value="${value.replace(/"/g, '&quot;')}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="{{ __('site.Option') }}">
                <button type="button" class="ml-2 text-red-600 hover:text-red-800 remove-option">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            optionsList.appendChild(optionDiv);
            
            // Añadir evento al botón de eliminar
            const removeButton = optionDiv.querySelector('.remove-option');
            removeButton.addEventListener('click', function() {
                optionDiv.remove();
                reindexOptions();
            });
        }
        
        function reindexOptions() {
            const optionsList = document.getElementById('options-list');
            if (!optionsList) return;
            
            const options = optionsList.querySelectorAll('input');
            options.forEach((input, index) => {
                input.name = `options[${index}]`;
            });
        }
        
        // Inicializar opciones si existen en el formulario
        @if(isset($question) && $question->options)
            @foreach($question->options as $option)
                addOption('{{ $option }}');
            @endforeach
        @endif
    });
</script>
@endpush
</x-app-layout>