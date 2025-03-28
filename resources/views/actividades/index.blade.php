@extends('adminlte::page')

@section('title', 'Calendario de Actividades')

@section('content_header')
    <h1>Calendario de Actividades</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Contenedor del calendario -->
        <div id="calendar"></div>
    </div>
</div>

<!-- Botón fijo para agendar actividad -->
<div class="fixed-bottom m-4 text-right">
    <a href="{{ route('actividades.create') }}" class="btn btn-success btn-lg">
        Agendar Actividad
    </a>
</div>

<!-- Modal para ver actividades del día -->
<div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dayModalLabel">
            Actividades para el día <span id="modalDate"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="border:none;background:none;font-size:1.5rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <ul class="list-group" id="dayActivitiesList">
              <!-- Se llenará dinámicamente -->
          </ul>
      </div>
      <div class="modal-footer">
        <a href="#" id="agendarBtn" class="btn btn-primary">Agendar Actividad para este día</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('css')
    <!-- Usar los enlaces correctos para FullCalendar v6 UMD -->
    
    <style>
        #calendar {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
@stop

@section('js')
    <!-- Cargar FullCalendar UMD -->
 <script src="
https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js
"></script>
    <!-- jQuery y Bootstrap JS para el modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendarEvents = @json($events);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: calendarEvents,
                dateClick: function(info) {
                    var clickedDate = info.dateStr; // Formato YYYY-MM-DD
                    var eventsForDay = calendarEvents.filter(function(event) {
                        var eventDate = new Date(event.start).toISOString().split('T')[0];
                        return eventDate === clickedDate;
                    });

                    document.getElementById('modalDate').textContent = clickedDate;
                    var list = document.getElementById('dayActivitiesList');
                    list.innerHTML = '';

                    if (eventsForDay.length > 0) {
                        eventsForDay.forEach(function(event) {
                            var li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = event.title + ' - ' + new Date(event.start).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            if (event.description) {
                                li.textContent += ' (' + event.description + ')';
                            }
                            list.appendChild(li);
                        });
                    } else {
                        var li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = 'No hay actividades programadas para este día.';
                        list.appendChild(li);
                    }
                    var agendarBtn = document.getElementById('agendarBtn');
                    agendarBtn.href = "{{ route('actividades.create') }}?fecha=" + clickedDate;
                    $('#dayModal').modal('show');
                },
                eventClick: function(info) {
                    alert('Actividad: ' + info.event.title + '\nFecha/Hora: ' + info.event.start);
                },
                aspectRatio: 2,
                expandRows: true
            });

            calendar.render();
        });
    </script>
@stop
