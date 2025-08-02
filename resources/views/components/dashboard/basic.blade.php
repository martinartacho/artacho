<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            <i class="bi bi-person"></i> {{ __('site.welcome') }}
        </h3>
        
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-dashboard.card title="{{ __('site.Profile') }}" color="gray">
                <i class="bi bi-person-circle"></i> {{ __('site.to_personal_information') }}
            </x-dashboard.card>

            <x-dashboard.card title="{{ __('site.Notifications') }}" color="blue">
                <i class="bi bi-bell-fill"></i> {{ __('site.assigned_notifications') }}
            </x-dashboard.card>

            <x-dashboard.card title="{{ __('site.Current_Language') }}" color="blue">
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
