@extends('adminlte::page')

@section('title', 'Detalles del Cliente')

@section('content_header')
    <h1>Detalles del Cliente</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $client->nombre }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Dirección:</strong> {{ $client->direccion ?? 'No especificada' }}</p>
            <p><strong>Teléfono:</strong> {{ $client->telefono ?? 'No especificado' }}</p>
            <p><strong>Email:</strong> {{ $client->correo ?? 'No especificado' }}</p>
            <!-- Agrega más campos según tus necesidades -->
        </div>
        <div class="card-footer">
            <a href="{{ route('clients.edit', $client->cliente_id) }}" class="btn btn-warning">Editar Cliente</a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</div>
@stop

@section('css')
    <!-- Puedes agregar estilos personalizados aquí -->
@stop

@section('js')
    <script>
        console.log('Vista de detalles del cliente cargada.');
    </script>
@stop
