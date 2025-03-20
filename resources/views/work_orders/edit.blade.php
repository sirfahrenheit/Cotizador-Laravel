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

        <!-- Avances -->
        <div class="form-group">
            <label for="avances">Avances</label>
            <textarea name="avances" id="avances" class="form-control" rows="3">{{ old('avances', $workOrder->avances) }}</textarea>
        </div>

        <!-- Solicitudes -->
        <div class="form-group">
            <label for="solicitudes">Solicitudes</label>
            <textarea name="solicitudes" id="solicitudes" class="form-control" rows="3">{{ old('solicitudes', $workOrder->solicitudes) }}</textarea>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="pendiente" {{ old('estado', $workOrder->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="finalizado" {{ old('estado', $workOrder->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
            </select>
        </div>

        <!-- Técnico Asignado -->
        <div class="form-group">
            <label for="tecnico_id">Técnico Asignado</label>
            <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                <option value="">Seleccione un técnico</option>
                @foreach($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}" {{ old('tecnico_id', $workOrder->tecnico_id) == $tecnico->id ? 'selected' : '' }}>
                        {{ $tecnico->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Actualizar Orden</button>
            <a href="{{ route('work_orders.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@stop

@section('css')
<style>
    /* Puedes agregar aquí estilos responsivos adicionales si lo requieres */
</style>
@stop

@section('js')
<script>
    console.log('Formulario de edición de orden de trabajo cargado.');
</script>
@stop
