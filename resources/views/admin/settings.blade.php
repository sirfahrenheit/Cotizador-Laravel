@extends('adminlte::page')

@section('title', 'Configuración')

@section('content_header')
    <h1>Configuración de la Aplicación</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="app_name">Nombre de la Aplicación</label>
            <input type="text" name="app_name" id="app_name" class="form-control" value="{{ old('app_name', $settings['app_name']) }}" required>
        </div>

        <div class="form-group">
            <label for="dark_mode">Modo Oscuro</label>
            <select name="dark_mode" id="dark_mode" class="form-control" required>
                <option value="0" {{ !$settings['dark_mode'] ? 'selected' : '' }}>Claro</option>
                <option value="1" {{ $settings['dark_mode'] ? 'selected' : '' }}>Oscuro</option>
            </select>
        </div>

        <!-- Agrega otros campos de configuración según lo necesites -->

        <button type="submit" class="btn btn-primary">Actualizar Configuración</button>
    </form>
@stop

@section('css')
    <style>
        /* Tus estilos personalizados */
    </style>
@stop

@section('js')
    <script>
        // Puedes agregar scripts si es necesario
    </script>
@stop
