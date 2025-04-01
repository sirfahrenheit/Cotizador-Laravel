@extends('adminlte::page')

@section('title', 'Detalles del Cliente')

@section('content_header')
    <h1>Detalles del Cliente</h1>
@stop

@section('content')
<div class="container">
    <!-- Información del cliente -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $client->nombre }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Dirección:</strong> {{ $client->direccion ?? 'No especificada' }}</p>
            <p><strong>Teléfono:</strong> {{ $client->telefono ?? 'No especificado' }}</p>
            <p><strong>Email:</strong> {{ $client->correo ?? 'No especificado' }}</p>
            <!-- Agrega más campos si es necesario -->
        </div>
        <div class="card-footer">
            <a href="{{ route('clients.edit', $client->cliente_id) }}" class="btn btn-warning">Editar Cliente</a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>

    <!-- Cotizaciones del cliente (Responsive sin tablas) -->
    @if($client->cotizaciones->count())
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Cotizaciones del Cliente</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($client->cotizaciones as $cotizacion)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Cotización #{{ $cotizacion->id }}</h5>
                                    <p class="card-text">
                                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cotizacion->created_at)->format('d/m/Y') }}<br>
                                        <strong>Total:</strong> Q{{ number_format($cotizacion->total, 2) }}<br>
                                        <strong>Estado:</strong> {{ ucfirst($cotizacion->status) }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn btn-primary btn-block">Ver Cotización</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-4" role="alert">
            Este cliente no tiene cotizaciones asignadas.
        </div>
    @endif
</div>
@stop

@section('css')
    <!-- Aquí puedes agregar estilos personalizados adicionales -->
@stop

@section('js')
    <script>
        console.log('Vista de detalles del cliente cargada.');
    </script>
@stop

