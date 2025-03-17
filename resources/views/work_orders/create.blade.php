@extends('adminlte::page')

@section('title', 'Crear Orden de Trabajo')

@section('content_header')
    <h1>Crear Orden de Trabajo</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('work_orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="tareas">Tareas (Instrucciones)</label>
            <textarea name="tareas" id="tareas" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="tecnico_id">Asignar a Técnico</label>
            <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                <option value="">Seleccione un técnico</option>
                @foreach($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Orden</button>
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
    console.log('Formulario de creación de orden de trabajo cargado.');
</script>
@stop
