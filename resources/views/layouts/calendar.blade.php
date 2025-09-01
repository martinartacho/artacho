<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', __('site.Event Calendar'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Styles específicos para el calendario -->
    <style>
        /* Estilos mínimos para la navegación */
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        
        /* Navigation styles */
        .navigation {
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 0;
        }
        
        .nav-container {
            max-width: 7xl;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        
        .nav-link {
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .nav-link:hover {
            color: #1f2937;
        }
        
        .nav-link.active {
            color: #2563eb;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .user-name {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        /* Calendar container */
        .calendar-container {
            max-width: 7xl;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .btn:hover {
            background-color: #2563eb;
        }
        
        .btn-gray {
            background-color: #6b7280;
        }
        
        .btn-gray:hover {
            background-color: #4b5563;
        }
        
        /* FullCalendar adjustments */
        #calendar {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            font-weight: 600;
        }
        
        .fc .fc-button {
            background-color: #3b82f6;
            border: 1px solid #3b82f6;
            font-weight: 600;
        }
        
        .fc .fc-button:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-links">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        @php
                            use App\Models\Setting;
                            $logoPath = Setting::get('logo');
                        @endphp
                        @if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath))
                            <img src="{{ asset('storage/' . $logoPath) }}" alt="Artacho"  style="max-height: 45px; height: 45px; width: auto; object-fit: contain;">
                        @else
                            <img src="{{ asset('img/logo.svg') }}" alt="Artacho"  style="max-height: 45px; height: 45px; width: auto; object-fit: contain;">
                        @endif
                    </a>
                </div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    {{ __('site.Dashboard') }}
                </a>
                <!-- Puedes añadir más enlaces de navegación aquí -->
                 <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    {{ __('site.Events') }}
                </a>
             
            </div>
            
            <div class="user-menu">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="calendar-container">
        <div class="calendar-header">
            <h1 class="text-2xl font-semibold text-gray-800">{{ __('site.Event Calendar') }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.events.index') }}" class="btn btn-gray">
                    {{ __('site.Back to Events List') }} {{ Auth::user()->name }}
                </a>
                @can('create', App\Models\Event::class)
                <a href="{{ route('admin.events.create') }}" class="btn">
                    {{ __('site.Create Event') }}
                </a>
                @endcan
            </div>
        </div>
        
        <div id="calendar"></div>
    </main>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/{{ app()->getLocale() }}.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '{{ route("admin.events.calendar-data") }}',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                locale: '{{ app()->getLocale() }}',
                firstDay: 1,
                buttonText: {
                    today: '{{ __("site.Today") }}',
                    month: '{{ __("site.Month") }}',
                    week: '{{ __("site.Week") }}',
                    day: '{{ __("site.Day") }}'
                },
                loading: function(bool) {
                    if (bool) {
                        document.getElementById('calendar').classList.add('opacity-50');
                    } else {
                        document.getElementById('calendar').classList.remove('opacity-50');
                    }
                }
            });
            
            calendar.render();
        });
    </script>
</body>
</html>