<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.Questions for Event: ') }} {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            {{ __('site.Events') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('admin.events.questions.index', $event) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                {{ $event->title }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ __('site.Create Question') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.events.questions.store', $event) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700">{{ __('site.Questions') }} *</label>
                            <input type="text" name="question" id="question" value="{{ old('question') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('question')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">{{ __('site.Type') }} *</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>{{ __('site.Text') }}</option>
                                <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>{{ __('site.Single Choice') }}</option>
                                <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>{{ __('site.Multiple Choice') }}</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4" id="options-container" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700">{{ __('site.Options') }}</label>
                            <div id="options-list">
                                <!-- Las opciones se añadirán dinámicamente -->
                            </div>
                            <button type="button" id="add-option" class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('site.Add Option') }}
                            </button>
                            @error('options')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="required" id="required" value="1" {{ old('required') ? 'checked' : '' }}
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="required" class="ml-2 block text-sm text-gray-900">{{ __('site.Required') }}</label>
                            </div>
                            @error('required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.events.questions.index', $event) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                {{ __('site.Cancel') }}
                            </a>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('site.Save') }}
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
            const typeSelect = document.getElementById('type');
            const optionsContainer = document.getElementById('options-container');
            const optionsList = document.getElementById('options-list');
            const addOptionButton = document.getElementById('add-option');

            function toggleOptions() {
                if (typeSelect.value === 'single' || typeSelect.value === 'multiple') {
                    optionsContainer.style.display = 'block';
                    
                    // Si no hay opciones, añadir una por defecto
                    if (optionsList.children.length === 0) {
                        addOption();
                    }
                } else {
                    optionsContainer.style.display = 'none';
                }
            }

            function addOption(value = '') {
                const optionIndex = optionsList.children.length;
                const optionDiv = document.createElement('div');
                optionDiv.className = 'flex items-center mb-2';
                optionDiv.innerHTML = `
                    <input type="text" name="options[${optionIndex}]" value="${value.replace(/"/g, '&quot;')}" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="{{ __('site.Option') }}">
                    <button type="button" class="ml-2 text-red-600 hover:text-red-900 remove-option">×</button>
                `;
                optionsList.appendChild(optionDiv);
            }

            typeSelect.addEventListener('change', toggleOptions);
            
            // Llamada inicial para configurar el estado correcto
            toggleOptions();

            addOptionButton.addEventListener('click', function() {
                addOption();
            });

            optionsList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-option')) {
                    e.target.parentElement.remove();
                    
                    // Reindexar opciones
                    const options = optionsList.querySelectorAll('input');
                    options.forEach((input, index) => {
                        input.name = `options[${index}]`;
                    });
                }
            });

            // Cargar opciones existentes si las hay
            @if(old('options') && is_array(old('options')))
                @foreach(old('options') as $option)
                    addOption(@json($option));
                @endforeach
            @endif
        });
    </script>
    @endpush
</x-app-layout>