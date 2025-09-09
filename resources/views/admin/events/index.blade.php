{{-- resources/views/admin/events/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
<h3 class="text-lg font-semibold">{{ __('site.Events List') }}</h3>
    <div class="space-x-2">

        @can('create', App\Models\Event::class)
            <a href="{{ route('admin.event-question-templates.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('site.Templates') }}
            </a>
            <a href="{{ route('admin.event-types.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('site.Event Types') }}
            </a>
            <a href="{{ route('admin.events.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('site.Create Event') }}
            </a>
        @endcan
    </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Title') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Type') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Start') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('End') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Visible') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Recurrence') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($events as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                        @if($event->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $event->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($event->eventType)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $event->eventType->color }}20; color: {{ $event->eventType->color }};">
                                            {{ $event->eventType->name }}
                                        </span>
                                        @else
                                        <span class="text-sm text-gray-500">{{ __('None') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->start->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->end ? $event->end->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $event->visible ? __('Yes') : __('No') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($event->recurrence_type !== 'none')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ __(ucfirst($event->recurrence_type)) }} 
                                                ({{ __('Every :interval', ['interval' => $event->recurrence_interval]) }})
                                            </span>
                                        @else
                                            <span class="text-gray-400">{{ __('None') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex flex-col space-y-1">
        <div class="flex space-x-2">
            <a href="{{ route('admin.events.edit', $event->id) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('site.Edit') }}</a>
            
            {{-- Enlace a preguntas del evento --}}
            <a href="{{ route('admin.events.questions.index', $event->id) }}" class="text-green-600 hover:text-green-900">
                {{ __('site.Questions') }} 
                @if($event->questions_count > 0)
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-1.5 py-0.5 rounded">
                    {{ $event->questions_count }}
                </span>
                @endif
            </a>
            
            {{-- Enlace a respuestas del evento --}}
            <a href="{{ route('admin.events.answers.index', $event->id) }}" class="text-purple-600 hover:text-purple-900">
                {{ __('site.Answers') }}
                @if($event->answers_count > 0)
                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-1.5 py-0.5 rounded">
                    {{ $event->answers_count }}
                </span>
                @endif
            </a>
        </div>
        
        @can('delete', $event)
        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('site.Are you sure you want to delete this event?') }}')">
                {{ __('site.Delete') }}
            </button>
        </form>
        @endcan
    </div>
</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('No events found.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($events->hasPages())
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>