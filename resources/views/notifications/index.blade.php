<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="bi bi-people-fill mr-2"></i>
                {{ __('site.Notifications') }}
            </h2>

            @can('create-notification')
            <a href="{{ route('notifications.create') }}">
                <x-primary-button>
                    <i class="bi bi-plus-lg mr-1"></i>{{ __('site.Create Notification') }} 
                </x-primary-button>
            </a>
            @endcan

        </div>
    </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Contenido de la vista index -->
                    <table class="table">
                        <thead>
                            <tr>

                                <th class="text-left px-2 py-2 w-1/3">{{ __('site.Title') }} </th>
                                <th class="text-left  px-2 py-2 w-1/5">{{ __('site.registration_date') }}</th>
                                 @can('publish-notification')
                                    <th class="text-left  px-2 py-2 w-2/20">{{ __('site.Sender') }}</th>
                                    <th class="text-left  px-2 py-2 w-2/20">{{ __('site.destinatarios') }}</th>
                                    <th class="text-left  px-2 py-2 w-2/20">{{ __('site.Published') }}</th> 
                                @endcan                              
                                <th class="text-left  px-2 py-2 w-2/20">{{ __('site.Actions') }}</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->title }}</td>
                                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>

                                    @can('publish-notification')
                                        <td>{{ $notification->sender->name }}</td>
                                        <td>
                                            @if($notification->recipient_type === 'all')
                                                {{ __('site.All_users') }}
                                            @elseif($notification->recipient_type === 'role')
                                                {{ $notification->recipient_role }}
                                            @else 
                                                {{ count($notification->recipient_ids) }} {{ __('site.Users') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($notification->is_published)
                                                <span class="badge bg-success"><i class="bi bi-journal-check"></i> {{ __('site.Published') }}</span>
                                            @else
                                                <span class="badge bg-warning"><i class="bi bi-journal"></i> {{ __('site.Not_published') }}</span>
                                            @endif
                                        </td>
                                    @endcan
                                    <td class="px-2 py-2 w-2/20">
                                        <div class="flex gap-x-1 items-center">
                                            @can('view-notification', $notification)
                                                <a href="{{ route('notifications.show', $notification) }}" 
                                                class="btn btn-sm btn-info" 
                                                title="{{ __('site.View_notification') }}">
                                                    <i class="bi {{ $notification->isRead() ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                                                </a>
                                            @endcan

                                            @can('edit-notification', $notification)
                                                <a href="{{ route('notifications.edit', $notification) }}" 
                                                class="btn btn-sm btn-primary" 
                                                title="{{ __('site.Edit_notification') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan

                                            @can('delete-notification', $notification)
                                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Estas seguro?')"
                                                        class="btn btn-sm btn-danger"
                                                        title="{{ __('site.Delete_notification') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('publish-notification')
                                                @unless($notification->is_published)
                                                    <form action="{{ route('notifications.publish', $notification) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="{{ __('site.Publish') }}">
                                                            <i class="bi bi-send-check"></i>
                                                        </button>
                                                    </form>
                                                @endunless

                                                @if($notification->is_published)
                                                    @if(!$notification->push_sent)
                                                        <form method="POST" action="{{ route('notifications.send-push', $notification) }}" class="d-inline mt-1">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary"
                                                                title="Enviar Push Ahora">
                                                                <i class="bi bi-phone"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-success mt-1"><i class="bi bi-app-indicator"></i></span>
                                                    @endif
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        <tbody>

                    </table>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
