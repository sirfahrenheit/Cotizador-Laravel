@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container">

    {{-- Sección para Técnicos --}}
    @if(Auth::user()->role == 'técnico' || Auth::user()->role == 'tecnico')
        <h2 class="mb-3">Mis Órdenes de Trabajo</h2>
        @if($workOrders->isEmpty())
            <div class="alert alert-info" role="alert">
                No tienes órdenes de trabajo asignadas.
            </div>
        @else
            <div class="row">
                @foreach($workOrders as $order)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Orden #{{ $order->orden_id }}</h5>
                                <p class="card-text">
                                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->fecha)->format('d/m/Y') }}<br>
                                    <strong>Estado:</strong> {{ ucfirst($order->estado) }}<br>
                                    <strong>Tareas:</strong> {{ \Illuminate\Support\Str::limit($order->tareas, 50) }}
                                </p>
                                <a href="{{ route('tech.work_orders.show', $order->orden_id) }}" class="btn btn-info btn-sm">
                                    Ver
                                </a>
                                <a href="{{ route('tech.work_orders.edit', $order->orden_id) }}" class="btn btn-warning btn-sm">
                                    Actualizar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    {{-- Sección para Administradores --}}
    @elseif(Auth::user()->role == 'admin')
        <h2 class="mb-3">Resumen de Cotizaciones y Ventas</h2>
        
        <div class="row">
            <!-- Tarjeta con gráfica de Total Cotizado vs. Total Vendido -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <strong>Cotizado vs. Vendido</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" style="max-width: 100%;"></canvas>
                        <div class="mt-3 text-center">
                            <p><strong>Total Cotizado:</strong> Q{{ number_format($totalQuoted, 2) }}</p>
                            <p><strong>Total Vendido:</strong> Q{{ number_format($totalSold, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta con gráfica de estatus de cotizaciones -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <strong>Estatus de Cotizaciones</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="quoteStatusChart" style="max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta con gráfica de Top productos vendidos -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <strong>Top Productos Vendidos</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductsChart" style="max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@stop

@section('css')
<style>
    .card {
        margin-bottom: 20px;
    }
</style>
@stop

@section('js')
<!-- Incluir Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(Auth::user()->role == 'admin')
        // =============== Gráfica: Cotizado vs. Vendido ===============
        var ctxSales = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: ['Total Cotizado', 'Total Vendido'],
                datasets: [{
                    label: 'Monto en Q',
                    data: [{{ $totalQuoted }}, {{ $totalSold }}],
                    backgroundColor: ['#5e2129', '#0F4C75'],
                    borderColor: ['#5e2129', '#0F4C75'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // =============== Gráfica: Estatus de Cotizaciones ===============
        // Asumiendo que $quoteStatusData = ['pendiente' => 5, 'autorizada' => 3, 'rechazada' => 2, ...]
        // Lo convertimos a un objeto JS:
        var quoteStatusData = @json($quoteStatusData ?? []);
        // Obtenemos las etiquetas y valores
        var statusLabels = Object.keys(quoteStatusData);
        var statusValues = Object.values(quoteStatusData);

        var ctxStatus = document.getElementById('quoteStatusChart').getContext('2d');
        var quoteStatusChart = new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Cotizaciones',
                    data: statusValues,
                    backgroundColor: [
                        '#3490dc', // azul
                        '#38c172', // verde
                        '#e3342f', // rojo
                        '#ffed4a', // amarillo
                        '#6c757d', // gris
                    ],
                }]
            },
            options: {
                responsive: true,
            }
        });

        // =============== Gráfica: Top Productos Vendidos ===============
        // Asumiendo que $topProductsData = ['Laptop' => 10, 'Monitor' => 7, 'Teclado' => 5, ...]
        var topProductsData = @json($topProductsData ?? []);
        var productLabels = Object.keys(topProductsData);
        var productValues = Object.values(topProductsData);

        var ctxProducts = document.getElementById('topProductsChart').getContext('2d');
        var topProductsChart = new Chart(ctxProducts, {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: productValues,
                    backgroundColor: '#1E90FF'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    @endif
});
</script>
@stop
