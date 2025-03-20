@extends('adminlte::page')

@section('title', 'Listado de Productos')

@section('content_header')
    <h1>Listado de Productos</h1>
@stop

@section('content')
<div class="container">
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Crear Producto</a>

    <!-- Barra de búsqueda -->
    <div class="mb-3">
        <form action="{{ route('products.index') }}" method="GET" class="form-inline">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por modelo..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-secondary">Buscar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Versión en tarjetas (mobile) -->
    <div class="card-layout">
        @forelse($products as $product)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->modelo }}</h5>
                    <p class="card-text">
                        <strong>Descripción:</strong> {{ $product->descripcion ?? 'No especificada' }}<br>
                        <strong>Precio:</strong> Q{{ number_format($product->precio, 2, '.', ',') }}
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('products.show', $product->producto_id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('products.edit', $product->producto_id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('products.destroy', $product->producto_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No se encontraron productos.</p>
        @endforelse
    </div>

    <!-- Versión en tabla (desktop) -->
    <div class="table-layout table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->producto_id }}</td>
                    <td>{{ $product->modelo }}</td>
                    <td>{{ $product->descripcion }}</td>
                    <td>Q{{ number_format($product->precio, 2, '.', ',') }}</td>
                    <td class="d-flex justify-content-start">
                        <a href="{{ route('products.show', $product->producto_id) }}" class="btn btn-info btn-sm mr-2">Ver</a>
                        <a href="{{ route('products.edit', $product->producto_id) }}" class="btn btn-warning btn-sm mr-2">Editar</a>
                        <form action="{{ route('products.destroy', $product->producto_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No se encontraron productos.</td>
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
    console.log('Listado de productos con vista responsive cargado.');
</script>
@stop
