@extends('adminlte::page')

@section('title', 'Mis Órdenes de Trabajo')

@section('content')
<div class="container-fluid">
    @if(!$isCheckedIn)
        <div class="card shadow-sm">
            <div class="card-body text-center">
                @php
                    $currentTime = \Carbon\Carbon::now('America/Guatemala')->format('H:i');
                    $checkInDeadline = "09:10";
                @endphp
                @if($currentTime < $checkInDeadline)
                    <h4>Por favor, realiza tu Check In</h4>
                    <p class="text-muted">Tienes hasta las 9:10 am para hacerlo a tiempo.</p>
                @else
                    <h4 class="text-danger">Estás tarde</h4>
                    <p class="text-muted">Aún puedes hacer el Check In, pero ya pasaste el horario.</p>
                @endif
                <button id="checkInBtn" class="btn btn-success">Check In</button>
            </div>
        </div>
    @else
        <h2>Mis Órdenes de Trabajo</h2>
        @if($orders->isEmpty())
            <div class="alert alert-info text-center">
                No tienes órdenes asignadas por el momento.
            </div>
        @else
            <div class="row">
                @foreach($orders as $order)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                Orden #{{ $order->id }}
                            </div>
                            <div class="card-body">
                                <p><strong>Descripción:</strong> {{ $order->tareas ?? 'Sin descripción' }}</p>
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->fecha)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('tech.work_orders.show', $order->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('tech.work_orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Actualizar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkInBtn = document.getElementById('checkInBtn');
    if (checkInBtn) {
        checkInBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const timestamp = new Date().toISOString();
                    fetch('/tech/work_orders/checkin', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng,
                            timestamp: timestamp
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Check-in registrado:', data);
                        // Puedes recargar la página o mostrar un mensaje al usuario
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error en el fetch:', error);
                    });
                });
            } else {
                alert('Tu navegador no soporta la geolocalización.');
            }
        });
    }
});
</script>
@stop
