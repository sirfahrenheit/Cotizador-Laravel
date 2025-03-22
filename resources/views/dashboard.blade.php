@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Resumen General</h1>
@stop

@section('content')
<div class="container-fluid">

    {{-- Fila de cajas de resumen (small boxes) --}}
    <div class="row">
        {{-- Caja 1: Autorizadas --}}
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

        {{-- Caja 2: Pendientes --}}
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

        {{-- Caja 3: Rechazadas --}}
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

        {{-- Caja 4: Vistas Totales --}}
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

        {{-- (Opcional) Caja 5: Total Cotizado o Vendido --}}
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

        {{-- (Opcional) Caja 6: Autorizado en dinero --}}
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
    </div><!-- /.row -->

    {{-- Fila con gráficas --}}
    <div class="row">
        {{-- Gráfica: Aceptadas por Cliente --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Aceptadas por Cliente</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartAceptadas"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfica: Rechazadas por Cliente --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Rechazadas por Cliente</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartRechazadas"></canvas>
                </div>
            </div>
        </div>
    </div><!-- /.row -->

</div><!-- /.container-fluid -->
@stop

@section('css')
<style>
    .small-box {
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .card {
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
</style>
@stop

@section('js')
{{-- Incluimos Chart.js desde un CDN (si no está ya en tu layout) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // =========== GRÁFICA: Aceptadas por Cliente =============
    const clientesAceptadas = @json($clientesAceptadas);
    const datosAceptadas    = @json($datosAceptadas);

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
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // =========== GRÁFICA: Rechazadas por Cliente =============
    const clientesRechazadas = @json($clientesRechazadas);
    const datosRechazadas    = @json($datosRechazadas);

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
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@stop
