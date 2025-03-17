@extends('adminlte::page')

@section('title', 'Listado de Cotizaciones')

@section('content_header')
    <h1>Listado de Cotizaciones</h1>
@stop

@section('content')
<div class="container">
    <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary mb-3">Crear Cotización</a>

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
                    <div class="mt-2">
                        <a href="{{ route('cotizaciones.show', $cotizacion->cotizacion_id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('cotizaciones.edit', $cotizacion->cotizacion_id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('cotizaciones.destroy', $cotizacion->cotizacion_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta cotización?')">Eliminar</button>
                        </form>
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
                        <td class="d-flex justify-content-start">
                            <a href="{{ route('cotizaciones.show', $cotizacion->cotizacion_id) }}" class="btn btn-info btn-sm mr-2">Ver</a>
                            <a href="{{ route('cotizaciones.edit', $cotizacion->cotizacion_id) }}" class="btn btn-warning btn-sm mr-2">Editar</a>
                            <form action="{{ route('cotizaciones.destroy', $cotizacion->cotizacion_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta cotización?')">Eliminar</button>
                            </form>
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
