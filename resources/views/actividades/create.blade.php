@extends('adminlte::page')

@section('title', 'Agendar Actividad')

@section('content_header')
    <h1>Agendar Actividad</h1>
@stop

@section('content')
<div class="container">
    @if($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach($errors->all() as $error)
                 <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form action="{{ route('actividades.store') }}" method="POST" id="actividadForm">
      @csrf

      <!-- Selección de Cliente con búsqueda -->
      <div class="mb-3">
         <label for="cliente_id" class="form-label">Cliente:</label>
         <select name="cliente_id" id="cliente_id" class="form-control" required>
           <option value="">Seleccione un cliente</option>
           @foreach($clientes as $cliente)
              <option value="{{ $cliente->cliente_id }}" {{ old('cliente_id') == $cliente->cliente_id ? 'selected' : '' }}>
                {{ $cliente->nombre }} - {{ $cliente->direccion }}
              </option>
           @endforeach
         </select>
      </div>

      <!-- Selección del Tipo de Actividad -->
      <div class="mb-3">
         <label for="tipo" class="form-label">Tipo de Actividad:</label>
         <select name="tipo" id="tipo" class="form-control" required>
           <option value="">Seleccione el tipo de actividad</option>
           <option value="Seguimiento de Cotización" {{ old('tipo') == 'Seguimiento de Cotización' ? 'selected' : '' }}>Seguimiento de Cotización</option>
           <option value="Llamada" {{ old('tipo') == 'Llamada' ? 'selected' : '' }}>Llamada</option>
           <option value="Reunión" {{ old('tipo') == 'Reunión' ? 'selected' : '' }}>Reunión</option>
           <option value="Instalación" {{ old('tipo') == 'Instalación' ? 'selected' : '' }}>Instalación</option>
         </select>
      </div>

      <!-- Campo para Fecha y Hora -->
      <div class="mb-3">
         <label for="fecha" class="form-label">Fecha y Hora:</label>
         <input type="datetime-local" name="fecha" id="fecha" class="form-control" required
            value="{{ request('fecha') ? date('Y-m-d\TH:i', strtotime(request('fecha'))) : old('fecha') }}">
      </div>

      <!-- Campo para Descripción -->
      <div class="mb-3">
         <label for="descripcion" class="form-label">Descripción (opcional):</label>
         <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ingrese detalles de la actividad">{{ old('descripcion') }}</textarea>
      </div>

      <!-- Botones de envío y cancelación -->
      <div class="mb-3 text-right">
         <button type="submit" class="btn btn-primary">Agendar Actividad</button>
         <a href="{{ route('actividades.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
</div>
@stop

@section('css')
    <!-- Estilos personalizados para el select (opcional) -->
    <style>
      .custom-select-no-arrow {
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        background-color: #fff;
        padding-right: 2.2rem;
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1rem;
      }
      .custom-select-no-arrow::-ms-expand {
        display: none;
      }
    </style>
    <!-- CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <!-- JS de Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
      $(document).ready(function() {
          // Inicializa Select2 para el select de clientes
          $('#cliente_id').select2({
              placeholder: "Seleccione un cliente",
              allowClear: true,
              width: '100%'
          });
      });
    </script>
@stop
