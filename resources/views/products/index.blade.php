@extends('adminlte::page')

@section('title', 'Listado de Productos')

@section('content_header')
    <h1>Listado de Productos</h1>
@stop

@section('content')
<div class="container">
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Crear Producto</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Versión en tarjetas (mobile) -->
    <div class="card-layout">
        @forelse($products as $product)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->modelo }}</h5>
                    <p class="card-text">
                        <strong>Descripción:</strong> {{ $product->descripcion ?? 'No especificada' }} <br>
                        <strong>Precio:</strong> Q {{ number_format($product->precio, 2, '.', ',') }} <br>
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('products.show', $product->producto_id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('products.edit', $product->producto_id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('products.destroy', $product->producto_id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                Eliminar
                            </button>
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
                    <td>Q {{ number_format($product->precio, 2, '.', ',') }}</td>
                    <td class="d-flex justify-content-start">
                        <a href="{{ route('products.show', $product->producto_id) }}" class="btn btn-info btn-sm mr-2">Ver</a>
                        <a href="{{ route('products.edit', $product->producto_id) }}" class="btn btn-warning btn-sm mr-2">Editar</a>
                        <form action="{{ route('products.destroy', $product->producto_id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                Eliminar
                            </button>
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
/* 
   Ocultamos la tabla en pantallas pequeñas (max-width: 767px)
   y mostramos el layout de tarjetas.
   A partir de 768px (md) en adelante, se hace lo contrario.
*/
@media (max-width: 767px) {
    .table-layout {
        display: none; /* Oculta la tabla en móviles */
    }
    .card-layout {
        display: block; /* Muestra las tarjetas en móviles */
    }
}

@media (min-width: 768px) {
    .table-layout {
        display: block; /* Muestra la tabla en escritorio */
    }
    .card-layout {
        display: none; /* Oculta las tarjetas en escritorio */
    }
}
</style>
@stop
