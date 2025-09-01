<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <title>Calendario de Eventos - Artacho</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            background-color: #f3f4f6;
        }
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            padding: 10px 15px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Calendario de Eventos</h1>
        <div>
            <a href="/admin/events" class="btn btn-gray">Volver a Eventos</a>
            <a href="/admin/events/create" class="btn">Crear Evento</a>
        </div>
    </div>
    
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/admin/events-calendar/data',
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
                locale: 'es',
                firstDay: 1,
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                }
            });
            
            calendar.render();
        });
    </script>
</body>
</html>