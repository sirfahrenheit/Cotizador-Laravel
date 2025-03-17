@extends('adminlte::page')

@section('title', 'Ver Orden de Trabajo')

@section('content_header')
    <h1>Orden de Trabajo #{{ $workOrder->orden_id }}</h1>
@stop

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-header">
            <h3>Detalles de la Orden</h3>
        </div>
        <div class="card-body">
            <p><strong>Fecha:</strong> {{ $workOrder->fecha }}</p>
            <p><strong>Tareas:</strong> {{ $workOrder->tareas }}</p>
            <p><strong>Avances:</strong> {{ $workOrder->avances }}</p>
            <p><strong>Solicitudes:</strong> {{ $workOrder->solicitudes }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($workOrder->estado) }}</p>
            <p><strong>Técnico:</strong> {{ $workOrder->tecnico ? $workOrder->tecnico->name : 'No asignado' }}</p>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('work_orders.index') }}" class="btn btn-secondary">Volver al listado</a>
        <a href="{{ route('work_orders.edit', $workOrder->orden_id) }}" class="btn btn-warning">Editar Orden</a>
        <form action="{{ route('work_orders.destroy', $workOrder->orden_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('¿Eliminar esta orden?')">Eliminar Orden</button>
        </form>
    </div>
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
<script>
    console.log('Orden de trabajo cargada.');
</script>
@stop
