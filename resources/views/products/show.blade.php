@extends('adminlte::page')

@section('title', 'Detalle de Producto')

@section('content_header')
    <h1>Detalle de Producto: {{ $product->modelo }}</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">{{ $product->modelo }}</h4>
        </div>
        <div class="card-body">
            <p class="mb-2">
                <strong>Descripción:</strong> {{ $product->descripcion ?? 'No especificada' }}
            </p>
            <p class="mb-2">
                <strong>Precio:</strong> Q {{ number_format($product->precio, 2, '.', ',') }}
            </p>
        </div>
        <div class="card-footer d-flex justify-content-start">
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
            <a href="{{ route('products.edit', $product->producto_id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
{{-- Puedes agregar estilos personalizados aquí --}}
@stop

@section('js')
<script>
    console.log('Vista de detalle de producto cargada.');
</script>
@stop
