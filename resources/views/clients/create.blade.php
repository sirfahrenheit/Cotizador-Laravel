@extends('adminlte::page')

@section('title', 'Crear Cliente')

@section('content_header')
    <h1>Crear Cliente</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="correo" id="correo" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Crear Cliente</button>
    </form>
</div>
@stop

@section('css')
    <!-- Estilos adicionales si es necesario -->
@stop

@section('js')
    <script>
        console.log('Crear cliente cargado.');
    </script>
@stop
