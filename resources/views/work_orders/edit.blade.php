@extends('adminlte::page')

@section('title', 'Editar Orden de Trabajo')

@section('content_header')
    <h1>Editar Orden de Trabajo #{{ $workOrder->orden_id }}</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('work_orders.update', $workOrder->orden_id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="avances">Avances</label>
            <textarea name="avances" id="avances" class="form-control" rows="3">{{ $workOrder->avances }}</textarea>
        </div>
        <div class="form-group">
            <label for="solicitudes">Solicitudes</label>
            <textarea name="solicitudes" id="solicitudes" class="form-control" rows="3">{{ $workOrder->solicitudes }}</textarea>
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
@stop

@section('css')
<style>
    /* Estilos adicionales */
</style>
@stop

@section('js')
<script>
    console.log('Formulario de edición de orden de trabajo cargado.');
</script>
@stop
