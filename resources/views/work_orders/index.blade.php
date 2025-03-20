@extends('adminlte::page')

@section('title', 'Listado de Órdenes de Trabajo')

@section('content_header')
    <h1>Órdenes de Trabajo</h1>
@stop

@section('content')
<div class="container">
    @if(Auth::user()->role == 'admin')
        <a href="{{ route('work_orders.create') }}" class="btn btn-primary mb-3">Crear Orden de Trabajo</a>
    @endif

    <!-- Buscador por fechas -->
    <form action="{{ route('work_orders.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4 mb-2">
                <input type="date" name="start_date" class="form-control" placeholder="Fecha inicio" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="date" name="end_date" class="form-control" placeholder="Fecha fin" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 mb-2">
                <button type="submit" class="btn btn-secondary btn-block">Buscar por Fecha</button>
            </div>
        </div>
    </form>

    <div class="row">
        @forelse($orders as $order)
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Orden #{{ $order->orden_id }}</h5>
                        <p class="card-text">
                            <strong>Fecha:</strong> {{ $order->fecha }}<br>
                            <strong>Tareas:</strong> {{ Str::limit($order->tareas, 30) }}<br>
                            <strong>Estado:</strong> {{ ucfirst($order->estado) }}<br>
                            <strong>Técnico:</strong> {{ $order->tecnico ? $order->tecnico->name : 'No asignado' }}
                        </p>
                    </div>
                    <div class="card-footer text-right d-flex flex-wrap justify-content-end">
                        <a href="{{ route('work_orders.show', $order->orden_id) }}" class="btn btn-info btn-sm m-1">Ver</a>
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('work_orders.edit', $order->orden_id) }}" class="btn btn-warning btn-sm m-1">Editar</a>
                            <form action="{{ route('work_orders.destroy', $order->orden_id) }}" method="POST" class="m-1" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta orden?')">Eliminar</button>
                            </form>
                        @elseif(Auth::user()->role == 'técnico' || Auth::user()->role == 'tecnico')
                            <a href="{{ route('work_orders.edit', $order->orden_id) }}" class="btn btn-warning btn-sm m-1">Actualizar</a>
                        @endif

                        <!-- Botón para enviar por WhatsApp al técnico -->
                        @if($order->tecnico && !empty($order->tecnico->telefono))
                            @php
                                $phone = $order->tecnico->telefono;
                                $message = "Hola, le envío la orden de trabajo #" . $order->orden_id . ". Por favor revise los detalles en: " . route('work_orders.show', $order->orden_id);
                                $whatsappLink = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($message);
                            @endphp
                            <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-success btn-sm m-1">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">No se encontraron órdenes de trabajo.</p>
            </div>
        @endforelse
    </div>
</div>
@stop

@section('css')
<style>
    /* Estilos para las cards */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
</style>
@stop

@section('js')
<script>
    console.log('Órdenes de trabajo cargadas.');
</script>
@stop
