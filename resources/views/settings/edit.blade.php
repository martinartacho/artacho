<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del sistema') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('settings.updateLogo') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- @method('PUT') -->

                    {{-- Logo --}}
                    <div class="mb-4">
                        <label for="logo" class="block text-sm font-medium text-gray-700">
                            {{ __('Logo del sistema') }}
                        </label>
                        <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept="image/*">
                    </div>


                    <div class="mt-6">
                        <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                    </div>
                </form>


                <form method="POST" action="{{ route('updateLanguage') }}">
                    @csrf
                    @method('PUT')

                    <div class="mt-4">
                        <label for="language" class="block font-medium text-sm text-gray-700">
                            {{ __('Idioma predeterminado del sistema') }}
                        </label>

                        <select name="language" id="language" class="mt-1 block w-full" required>
                            <option value="en" {{ $settings['language'] === 'en' ? 'selected' : '' }}>EN</option>
                            <option value="es" {{ $settings['language'] === 'es' ? 'selected' : '' }}>ES</option>
                            <option value="ca" {{ $settings['language'] === 'ca' ? 'selected' : '' }}>CA</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Guardar') }}
                        </button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</x-app-layout>
