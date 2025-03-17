@extends('layouts.tech')

@section('title', 'Ver Orden de Trabajo')

@section('content')
<div class="container">
    <h2>Orden de Trabajo #{{ $workOrder->orden_id }}</h2>
    
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Fecha:</strong> {{ $workOrder->fecha }}</p>
            <p><strong>Tareas:</strong> {{ $workOrder->tareas }}</p>
            <p><strong>Avances:</strong> {{ $workOrder->avances ?? 'No registrados' }}</p>
            <p><strong>Solicitudes:</strong> {{ $workOrder->solicitudes ?? 'No registradas' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($workOrder->estado) }}</p>
        </div>
    </div>

    <a href="{{ route('tech.work_orders.edit', $workOrder->orden_id) }}" class="btn btn-warning">Actualizar Avances/Solicitudes</a>
</div>
@endsection
