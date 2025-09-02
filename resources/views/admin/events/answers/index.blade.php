<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.Answers for Event: ') }} {{ $event->title }}
        </h2>
    </x-slot>

        <style>
        .options-container {
            transition: all 0.3s ease;
        }
        
        .option-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .option-input {
            flex: 1;
        }
        
        .remove-option {
            cursor: pointer;
            padding: 0.5rem;
            margin-left: 0.5rem;
            border-radius: 0.25rem;
            background-color: #fef2f2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remove-option:hover {
            background-color: #fee2e2;
        }
        
        #add-option {
            margin-top: 0.5rem;
        }
        
        .option-required {
            color: #ef4444;
        }
    </style>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <a href="{{ route('admin.events.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('site.Back to Events List') }}
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.events.answers.create', $event) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('site.Add Answer') }}
                            </a>
                        </div>
                    </div>

                    @if($answers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('site.User') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('site.Questions') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('site.Answer') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('site.Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($answers as $answer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $answer->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $answer->question->question }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ Str::limit($answer->answer, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.events.answers.show', [$event, $answer]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('site.View') }}</a>
                                        <a href="{{ route('admin.events.answers.edit', [$event, $answer]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('site.Edit') }}</a>
                                        <form action="{{ route('admin.events.answers.destroy', [$event, $answer]) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('site.Are you sure?') }}')">{{ __('site.Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-gray-500">{{ __('site.No answers found for this event.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>