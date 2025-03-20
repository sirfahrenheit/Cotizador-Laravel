@extends('adminlte::page')

@section('title', 'Listado de Cotizaciones')

@section('content_header')
    <h1>Listado de Cotizaciones</h1>
@stop

@section('content')
<div class="container">
    <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary mb-3">Crear Cotización</a>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('cotizaciones.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar cotización por número" value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Versión en tarjetas para móviles -->
    <div class="card-layout">
        @forelse($cotizaciones as $cotizacion)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Cotización #{{ $cotizacion->cotizacion_numero }}</h5>
                    <p class="card-text">
                        <strong>Cliente:</strong> {{ $cotizacion->client ? $cotizacion->client->nombre : 'N/A' }}<br>
                        <strong>Dirección:</strong> {{ $cotizacion->client ? $cotizacion->client->direccion : 'No especificada' }}<br>
                        <strong>Estado:</strong> {{ ucfirst($cotizacion->status) }}<br>
                        <strong>Total:</strong> Q{{ number_format($cotizacion->total, 2) }}
                    </p>
                    <div class="mt-2 d-flex flex-wrap">
                        <a href="{{ route('cotizaciones.show', $cotizacion->cotizacion_id) }}" class="btn btn-info btn-sm mr-2 mb-2">Ver</a>
                        <a href="{{ route('cotizaciones.edit', $cotizacion->cotizacion_id) }}" class="btn btn-warning btn-sm mr-2 mb-2">Editar</a>
                        <form action="{{ route('cotizaciones.destroy', $cotizacion->cotizacion_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm mr-2 mb-2" onclick="return confirm('¿Está seguro de eliminar esta cotización?')">Eliminar</button>
                        </form>
                        <!-- Botón para enviar por WhatsApp -->
                        @if($cotizacion->client && !empty($cotizacion->client->telefono))
                            @php
                                // Genera el link para WhatsApp con el número del cliente y un mensaje predefinido
                                $whatsAppNumber = preg_replace('/\D/', '', $cotizacion->client->telefono);
                                $message = urlencode("Hola {$cotizacion->client->nombre}, consulte la cotización #{$cotizacion->cotizacion_numero} en: " . route('quotes.public_view', ['token' => $cotizacion->cotizacion_token]));
                                $whatsAppLink = "https://wa.me/{$whatsAppNumber}?text={$message}";
                            @endphp
                            <a href="{{ $whatsAppLink }}" target="_blank" class="btn btn-success btn-sm mb-2">Enviar WhatsApp</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No hay cotizaciones registradas.</p>
        @endforelse
    </div>

    <!-- Versión en tabla para escritorio -->
    <div class="table-layout table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cotizaciones as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->cotizacion_id }}</td>
                        <td>{{ $cotizacion->cotizacion_numero }}</td>
                        <td>{{ $cotizacion->client ? $cotizacion->client->nombre : 'N/A' }}</td>
                        <td>{{ $cotizacion->client ? $cotizacion->client->direccion : 'No especificada' }}</td>
                        <td>{{ ucfirst($cotizacion->status) }}</td>
                        <td>Q{{ number_format($cotizacion->total, 2) }}</td>
                        <td class="d-flex flex-wrap">
                            <a href="{{ route('cotizaciones.show', $cotizacion->cotizacion_id) }}" class="btn btn-info btn-sm mr-2 mb-2">Ver</a>
                            <a href="{{ route('cotizaciones.edit', $cotizacion->cotizacion_id) }}" class="btn btn-warning btn-sm mr-2 mb-2">Editar</a>
                            <form action="{{ route('cotizaciones.destroy', $cotizacion->cotizacion_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm mr-2 mb-2" onclick="return confirm('¿Eliminar esta cotización?')">Eliminar</button>
                            </form>
                            <!-- Botón para enviar por WhatsApp -->
                            @if($cotizacion->client && !empty($cotizacion->client->telefono))
                                @php
                                    $whatsAppNumber = preg_replace('/\D/', '', $cotizacion->client->telefono);
                                    $message = urlencode("Hola {$cotizacion->client->nombre}, consulte la cotización #{$cotizacion->cotizacion_numero} en: " . route('quotes.public_view', ['token' => $cotizacion->cotizacion_token]));
                                    $whatsAppLink = "https://wa.me/{$whatsAppNumber}?text={$message}";
                                @endphp
                                <a href="{{ $whatsAppLink }}" target="_blank" class="btn btn-success btn-sm mb-2">Enviar WhatsApp</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay cotizaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<style>
    @media (max-width: 767px) {
        .table-layout {
            display: none;
        }
        .card-layout {
            display: block;
        }
    }
    @media (min-width: 768px) {
        .table-layout {
            display: block;
        }
        .card-layout {
            display: none;
        }
    }
</style>
@stop

@section('js')
<script>
    console.log('Listado de cotizaciones cargado.');
</script>
@stop
