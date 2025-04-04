@extends('adminlte::page')

@section('title', 'Listado de Clientes')

@section('content_header')
    <h1>Listado de Clientes</h1>
@stop

@section('content')
<div class="container">
    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Crear Cliente</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Barra de búsqueda -->
    <div class="mb-3">
        <form action="{{ route('clients.index') }}" method="GET" class="form-inline">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-secondary">Buscar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Versión en tarjetas (mobile) -->
    <div class="card-layout">
        @forelse($clients as $client)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $client->nombre }}</h5>
                    <p class="card-text">
                        <strong>Dirección:</strong> {{ $client->direccion ?? 'No especificada' }} <br>
                        <strong>Teléfono:</strong> {{ $client->telefono ?? 'No especificado' }} <br>
                        <strong>Correo:</strong> {{ $client->correo ?? 'No especificado' }}
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('clients.show', $client->cliente_id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('clients.edit', $client->cliente_id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('clients.destroy', $client->cliente_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este cliente?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No se encontraron clientes.</p>
        @endforelse
    </div>

    <!-- Versión en tabla (desktop) -->
    <div class="table-layout table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>{{ $client->cliente_id }}</td>
                    <td>{{ $client->nombre }}</td>
                    <td>{{ $client->direccion }}</td>
                    <td>{{ $client->telefono }}</td>
                    <td>{{ $client->correo }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('clients.show', $client->cliente_id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('clients.edit', $client->cliente_id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('clients.destroy', $client->cliente_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este cliente?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron clientes.</td>
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
    console.log('Listado de clientes con vista responsive.');
</script>
@stop
