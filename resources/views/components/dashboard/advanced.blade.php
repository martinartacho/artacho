<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            <i class="bi bi-person-gear"></i> {{ __('site.welcome') }}
        </h3>
        
       <div class="mt-4 grid grid-cols-1 gap-4">
            <!-- Primera fila: Cards normales en 3 columnas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-dashboard.card title="{{ __('site.Setting') }}" color="purple">
                    <i class="bi bi-sliders"></i> {{ __('site.full_access') }}
                </x-dashboard.card>

                <x-dashboard.card title="{{ __('site.Users_role_persions') }}" color="indigo">
                    <i class="bi bi-people"></i> {{ __('site.User_role_management') }}
                </x-dashboard.card>

                <x-dashboard.card title="{{ __('site.Notifications') }}" color="blue">
                    <i class="bi bi-bell"></i> {{ __('site.Notification_management') }}
                </x-dashboard.card>
            </div>

            <!-- Segunda fila: Card de idioma ocupando todo el ancho -->
            <div class="col-span-full">
                <x-dashboard.card title="{{ __('site.Current_Language') }}" color="green">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="bi bi-translate text-xl mr-3"></i>
                            @switch(app()->getLocale())
                                @case('es') {{ __('site.Spanish') }} @break
                                @case('ca') {{ __('site.Catalonia') }} @break
                                @default  {{ __('site.English') }}
                            @endswitch
                        </div>
                        
                        @if($languageConflict = session('language_conflict'))
                        <div class="flex items-center text-yellow-600">
                            <i class="bi bi-exclamation-triangle mr-2"></i>
                            <span>{{ __('site.Conflict_detected') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($languageConflict = session('language_conflict'))
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="bi bi-info-circle text-yellow-600 mr-2"></i>
                            <span class="text-yellow-700 font-medium">
                                {{ __('site.LanguageConflictWarning') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                            <form method="POST" action="{{ route('language.resolve-conflict') }}" 
                                class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                @csrf
                                <input type="hidden" name="action" value="use_user">
                                <button type="submit" class="w-full text-left">
                                    <div class="flex items-center">
                                        <i class="bi bi-arrow-repeat text-blue-500 mr-2"></i>
                                        <div>
                                            <p class="font-medium">{{ __('site.UseMyPreferred') }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ __("site." . ucfirst($languageConflict['user_language'])) }} 
                                                ({{ __('site.Your_preference') }})
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('language.resolve-conflict') }}" 
                                class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                @csrf
                                <input type="hidden" name="action" value="use_session">
                                <button type="submit" class="w-full text-left">
                                    <div class="flex items-center">
                                        <i class="bi bi-check-circle text-green-500 mr-2"></i>
                                        <div>
                                            <p class="font-medium">{{ __('site.KeepCurrent') }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ __("site." . ucfirst($languageConflict['session_language'])) }} 
                                                ({{ __('site.Current_session') }})
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('language.resolve-conflict') }}" 
                                class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                @csrf
                                <input type="hidden" name="action" value="update_preference">
                                <button type="submit" class="w-full text-left">
                                    <div class="flex items-center">
                                        <i class="bi bi-pencil-square text-purple-500 mr-2"></i>
                                        <div>
                                            <p class="font-medium">{{ __('site.UpdateMyPreference') }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ __("site." . ucfirst($languageConflict['session_language'])) }} 
                                                ({{ __('site.New_preference') }})
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </x-dashboard.card>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            </div>
        </div>
    </div>
</div>
