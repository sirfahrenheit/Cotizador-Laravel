@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container">
    @if(Auth::user()->role == 'técnico' || Auth::user()->role == 'tecnico')
        <h2>Mis Órdenes de Trabajo</h2>
        @if($workOrders->isEmpty())
            <p>No tienes órdenes de trabajo asignadas.</p>
        @else
            <div class="row">
                @foreach($workOrders as $order)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Orden #{{ $order->orden_id }}</h5>
                                <p class="card-text">
                                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->fecha)->format('d/m/Y') }}<br>
                                    <strong>Estado:</strong> {{ ucfirst($order->estado) }}<br>
                                    <strong>Tareas:</strong> {{ \Illuminate\Support\Str::limit($order->tareas, 50) }}
                                </p>
                                <a href="{{ route('tech.work_orders.show', $order->orden_id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('tech.work_orders.edit', $order->orden_id) }}" class="btn btn-warning btn-sm">Actualizar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @elseif(Auth::user()->role == 'admin')
        <h2>Resumen de Cotizaciones y Ventas</h2>
        <div class="card mb-3">
            <div class="card-body text-center">
                <canvas id="salesChart" style="max-width: 600px; margin: auto;"></canvas>
                <div class="mt-3">
                    <p><strong>Total Cotizado:</strong> Q{{ number_format($totalQuoted, 2) }}</p>
                    <p><strong>Total Vendido:</strong> Q{{ number_format($totalSold, 2) }}</p>
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
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
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
    @endif
});
</script>
@stop
