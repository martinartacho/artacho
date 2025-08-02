<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.Dashboard') }} 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @role('admin|gestor|editor')
                <x-dashboard.advanced />
            @else
                <x-dashboard.basic />
            @endrole
        </div>
    </div>
</x-app-layout>