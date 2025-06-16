<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📲 {{ __('site.Push Logs') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium mb-4">Archivos de logs de notificaciones push</h3>

            @if($logs->isEmpty())
                <p>No hay archivos de log disponibles.</p>
            @else
                <ul class="list-disc list-inside space-y-2">
                    @foreach ($logs as $log)
                        <li class="flex items-center justify-between">
                            <span>{{ $log->getFilename() }} ({{ \Carbon\Carbon::createFromTimestamp($log->getCTime())->diffForHumans() }})</span>
                            <a href="{{ route('push.logs.download', $log->getFilename()) }}" class="text-blue-600 hover:underline">
                                Descargar
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
