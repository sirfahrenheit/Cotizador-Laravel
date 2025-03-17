@extends('layouts.tech')

@section('title', 'Actualizar Orden de Trabajo')

@section('content')
<div class="container">
    <h2>Actualizar Orden de Trabajo #{{ $workOrder->orden_id }}</h2>
    <form action="{{ route('tech.work_orders.update', $workOrder->orden_id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="avances">Avances</label>
            <textarea name="avances" id="avances" class="form-control" rows="3" required>{{ $workOrder->avances }}</textarea>
        </div>
        <div class="form-group">
            <label for="solicitudes">Solicitudes</label>
            <textarea name="solicitudes" id="solicitudes" class="form-control" rows="3" required>{{ $workOrder->solicitudes }}</textarea>
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="pendiente" {{ $workOrder->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="finalizado" {{ $workOrder->estado == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Orden</button>
    </form>
</div>
@endsection
