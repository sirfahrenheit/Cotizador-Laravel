@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
<div class="container">
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ingrese el modelo" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ingrese la descripción" required></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" name="precio" id="precio" class="form-control" step="0.01" placeholder="Ingrese el precio" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Producto</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
    console.log('Vista Create Producto cargada');
</script>
@stop
