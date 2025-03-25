@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Bienvenido, {{ Auth::user()->name }}</h1>
@stop

@section('content')
<div class="container-fluid">
    @if($role === 'admin')
        <!-- Sección para Administradores -->
        <h2>Resumen General</h2>
        <div class="row">
            <!-- Caja: Autorizadas -->
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $autorizadasCount }}</h3>
                        <p>Autorizadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <!-- Caja: Pendientes -->
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $pendientesCount }}</h3>
                        <p>Pendientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <!-- Caja: Rechazadas -->
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $rechazadasCount }}</h3>
                        <p>Rechazadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <!-- Caja: Vistas Totales -->
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $vistasTotales }}</h3>
                        <p>Vistas Totales</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
            </div>
            <!-- Caja: Total Cotizado -->
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>Q{{ number_format($totalQuoted, 2) }}</h3>
                        <p>Total Cotizado</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <!-- Caja: Total Autorizado -->
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>Q{{ number_format($totalSold, 2) }}</h3>
                        <p>Total Autorizado</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila de gráficas -->
        <div class="row">
            <!-- Gráfica: Aceptadas por Cliente -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <strong>Aceptadas por Cliente</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartAceptadas" style="max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <!-- Gráfica: Rechazadas por Cliente -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <strong>Rechazadas por Cliente</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartRechazadas" style="max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de notificaciones en tiempo real -->
        <div class="row">
            <div class="col-12">
                <div id="realtimeNotification" class="alert alert-info d-none" role="alert">
                    Notificación: Una orden de trabajo ha sido actualizada.
                </div>
            </div>
        </div>
    @elseif($role === 'técnico' || $role === 'tecnico')
        <!-- Sección para Técnicos -->
        @php
            // Determinar si el técnico está tarde comparando la hora actual con el límite (America/Guatemala)
            $currentTime = \Carbon\Carbon::now('America/Guatemala')->format('H:i');
            $checkInDeadline = "09:10";
            $isLate = $currentTime >= $checkInDeadline;
        @endphp

        @if(!$isCheckedIn)
            <!-- Si no ha hecho el check-in, se muestra la sección de check-in -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if(!$isLate)
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
            <!-- Si ya realizó el check-in, se muestran sus órdenes asignadas -->
            <h2>Mis Órdenes de Trabajo</h2>
            @if($workOrders->isEmpty())
                <div class="alert alert-info text-center">
                    No tienes órdenes asignadas por el momento.
                </div>
            @else
                <div class="row">
                    @foreach($workOrders as $order)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    Orden #{{ $order->id }}
                                </div>
                                <div class="card-body">
                                    <p><strong>Descripción:</strong> {{ $order->description ?? 'Sin descripción' }}</p>
                                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="card-footer text-right">
                                    <!-- Usamos route-model binding pasando el objeto $order -->
                                    <a href="{{ route('tech.work_orders.show', $order) }}" class="btn btn-info btn-sm">Ver</a>
                                    <a href="{{ route('tech.work_orders.edit', $order) }}" class="btn btn-warning btn-sm">Actualizar</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    @endif
</div>
@stop

@section('css')
<style>
    .small-box {
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .card {
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
</style>
@stop

@section('js')
@if($role === 'admin')
    <!-- Scripts para Admin: Chart.js, Pusher y Laravel Echo -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="{{ asset('js/echo.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gráfica: Aceptadas por Cliente
        const clientesAceptadas = @json($clientesAceptadas);
        const datosAceptadas = @json($datosAceptadas);
        const ctxAceptadas = document.getElementById('chartAceptadas').getContext('2d');
        new Chart(ctxAceptadas, {
            type: 'bar',
            data: {
                labels: clientesAceptadas,
                datasets: [{
                    label: 'Aceptadas',
                    data: datosAceptadas,
                    backgroundColor: '#28a745'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Gráfica: Rechazadas por Cliente
        const clientesRechazadas = @json($clientesRechazadas);
        const datosRechazadas = @json($datosRechazadas);
        const ctxRechazadas = document.getElementById('chartRechazadas').getContext('2d');
        new Chart(ctxRechazadas, {
            type: 'bar',
            data: {
                labels: clientesRechazadas,
                datasets: [{
                    label: 'Rechazadas',
                    data: datosRechazadas,
                    backgroundColor: '#dc3545'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Laravel Echo para notificaciones en tiempo real
        window.Echo.channel('work-orders')
            .listen('WorkOrderUpdated', (e) => {
                const notificationEl = document.getElementById('realtimeNotification');
                notificationEl.classList.remove('d-none');
                notificationEl.textContent = 'Notificación: Orden de trabajo #' + e.order_id + ' actualizada.';
                setTimeout(() => { notificationEl.classList.add('d-none'); }, 5000);
            });
    });
    </script>
@endif

@if($role === 'técnico' || $role === 'tecnico')
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
                            return response.text();
                        })
                        .then(text => {
                            alert("Check-in registrado correctamente.\n" + text);
                            location.reload();
                        })
                        .catch(error => {
                            alert("Error en el check-in: " + error);
                        });
                    }, function(error) {
                        alert("Error al obtener la geolocalización: " + error.message);
                    });
                } else {
                    alert("Tu navegador no soporta la geolocalización.");
                }
            });
        }
    });
    </script>
@endif
@stop
