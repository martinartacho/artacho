<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('site.Event Calendar') }} - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .filters {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .btn:hover {
            background-color: #2563eb;
        }
        
        #calendar {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        /* Ajustes específicos para FullCalendar */
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            font-weight: 600;
        }
        
        .fc-event {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 1.5rem; font-weight: 600;">{{ __('site.Event Calendar') }}</h1>
            <div>
                @auth
                    <a href="{{ url('dashboard') }}" class="btn">{{ __('site.Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn">{{ __('site.Login') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn" style="background-color: #6b7280;">{{ __('site.Register') }}</a>
                    @endif
                @endauth
            </div>
        </div>
        
        <div class="filters">
            <label for="event_type_filter">{{ __('site.Filter by Type') }}:</label>
            <select id="event_type_filter" class="event-type-filter">
                <option value="">{{ __('site.All Types') }}</option>
                @foreach($eventTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div id="calendar"></div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/{{ app()->getLocale() }}.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var eventTypeFilter = document.getElementById('event_type_filter');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(fetchInfo, successCallback, failureCallback) {
                    var url = '{{ route("calendar.events") }}?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr;
                    
                    if (eventTypeFilter.value) {
                        url += '&event_type_id=' + eventTypeFilter.value;
                    }
                    
                    fetch(url)
                        .then(response => response.json())
                        .then(events => successCallback(events))
                        .catch(error => failureCallback(error));
                },
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
                }
            });
            
            calendar.render();
            
            // Aplicar filtro cuando cambie el tipo de evento
            eventTypeFilter.addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
</body>
</html>