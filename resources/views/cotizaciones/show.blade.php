@extends('adminlte::page')

@section('title', 'Ver Cotización')

@section('content_header')
    <h1>Cotización #{{ $cotizacion->cotizacion_numero }}</h1>
@stop

@section('content')
<div class="container">
    <!-- Datos Generales -->
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Datos Generales</h3>
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $cotizacion->client ? $cotizacion->client->nombre : 'N/A' }}</p>
            <p><strong>Dirección:</strong> {{ $cotizacion->client ? $cotizacion->client->direccion : 'No especificada' }}</p>
            <p><strong>Teléfono:</strong> {{ $cotizacion->client ? $cotizacion->client->telefono : 'N/A' }}</p>
            <p><strong>Correo:</strong> {{ $cotizacion->client ? $cotizacion->client->correo : 'N/A' }}</p>
            <p><strong>Fecha de Expiración:</strong> {{ $cotizacion->expiration_date }}</p>
            <p><strong>Condiciones de Pago:</strong> {{ $cotizacion->payment_conditions }}</p>
            <p><strong>Comentarios Adicionales:</strong> {{ $cotizacion->additional_notes }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($cotizacion->status) }}</p>
        </div>
    </div>

    <!-- Ítems (Productos y Servicios) -->
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Productos y Servicios</h3>
        </div>
        <div class="card-body">
            @if($cotizacion->items->isEmpty())
                <p>No se encontraron ítems para esta cotización.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Modelo</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cotizacion->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->modelo }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Q{{ number_format($item->unit_price, 2) }}</td>
                                    <td>Q{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Totales -->
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Totales</h3>
        </div>
        <div class="card-body">
            <p><strong>Subtotal:</strong> Q{{ number_format($subtotal, 2) }}</p>
            <p><strong>Descuento:</strong> Q{{ number_format($descuentoTotal, 2) }}</p>
            <p style="font-weight:bold;"><strong>Total:</strong> Q{{ number_format($total, 2) }}</p>
        </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex flex-wrap justify-content-center mt-3">
        <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary m-1">Volver al listado</a>
        <a href="{{ route('cotizaciones.edit', $cotizacion->cotizacion_id) }}" class="btn btn-warning m-1">Editar Cotización</a>
        <a href="{{ route('quotes.public_view', ['token' => $cotizacion->cotizacion_token]) }}" class="btn btn-info m-1">Vista Cliente</a>
        <a href="{{ route('quotes.pdf', ['token' => $cotizacion->cotizacion_token]) }}" class="btn btn-primary m-1">Descargar PDF</a>
        @if($cotizacion->status === 'pendiente')
            <form action="{{ route('cotizaciones.authorize', $cotizacion->cotizacion_id) }}" method="POST" class="m-1">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success" onclick="return confirm('¿Autorizar esta cotización?')">Autorizar Cotización</button>
            </form>
        @endif
        <form action="{{ route('cotizaciones.destroy', $cotizacion->cotizacion_id) }}" method="POST" class="m-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cotización?')">Eliminar Cotización</button>
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
    console.log('Vista de cotización cargada.');
</script>
@stop
