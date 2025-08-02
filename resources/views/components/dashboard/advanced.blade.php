<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            <i class="bi bi-person-gear"></i> {{ __('site.welcome') }}
        </h3>
        
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-dashboard.card title="{{ __('site.Setting') }}" color="purple">
                <i class="bi bi-sliders"></i> {{ __('site.full_access') }}
            </x-dashboard.card>

            <x-dashboard.card title="{!! __('site.Users_role_persions') !!}" color="indigo">
                <i class="bi bi-people"></i> {{ __('site.User_role_management') }}
            </x-dashboard.card>

            <x-dashboard.card title="{{ __('site.Notifications') }}" color="blue">
                <i class="bi bi-bell"></i> {{ __('site.Notification_management') }}
            </x-dashboard.card>

            <!-- Idioma actual en uso -->
            <x-dashboard.card title="{{ __('site.Current_Language') }}" color="green">
                <i class="bi bi-translate"></i>
                @switch($currentLanguage)
                    @case('es') {{ __('site.Spanish') }} @break
                    @case('ca') {{ __('site.Catalonia') }} @break
                    @default  {{ __('site.English') }}
                @endswitch
            </x-dashboard.card>

        </div>
    </div>
</div>
