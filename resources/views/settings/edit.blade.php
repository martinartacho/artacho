<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del sitio') }}
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
                            {{ __('Logo del sitio') }}
                        </label>
                        <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept="image/*">
                    </div>


                    <div class="mt-6">
                        <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                    </div>
                </form>

                {{-- Language --}}
                <form method="POST" action="{{ route('settings.updateLanguage') }}" enctype="multipart/form-data">
                    @csrf
                     @method('PUT')
                    <div class="col-span-1">
                        <x-input-label :value="__('site.Default Language')" />
                        <div class="mt-2 space-y-2">
                            @foreach([
                                'es' => __('site.Spanish'),
                                'ca' => __('site.Catalonia'),
                                'en' => __('site.English'),
                            ] as $code => $label)
                                <label class="flex items-center">
                                    <input type="radio" 
                                        name="language" 
                                        value="{{ $code }}"
                                        {{ $code == $settings['language'] ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                    </div>
                </form>



                {{-- Sección de Logs Push --}}
                <div class="mt-10">
                    <h2 class="text-xl font-semibold mb-4">
                        {{ __('site.Push') }}
                    </h2>
                    <p class="text-gray-600 mb-4">
                        {{ __('site.Push log files') }}
                    </p>

                    @if($settings['pushLogs']->isEmpty())
                    <p>{{ __('site.No logs available') }}.</p>
                @else
                    <ul>
                        @foreach($settings['pushLogs'] as $log)
                            <li>
                                {{ $log->getFilename() }}
                                <a href="{{ route('push.logs.download', $log->getFilename()) }}" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-download"></i>   {{ __('site.Download') }}
                                </a>

                                <form method="POST" action="{{ route('push.logs.delete', $log->getFilename()) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este log?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>  {{ __('site.Delete') }}
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                </div>




            </div>
        </div>
    </div>
</x-app-layout>
