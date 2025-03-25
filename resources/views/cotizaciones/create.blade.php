@extends('adminlte::page')

@section('title', 'Crear Cotización')

@section('content_header')
    <h1>Crear Cotización</h1>
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

    <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
      @csrf

      <!-- Datos Generales -->
      <div class="mb-3">
         <label for="cliente_id" class="form-label">Cliente:</label>
         <select name="cliente_id" id="cliente_id" class="form-control" required>
           <option value="">Seleccione un cliente</option>
           @foreach($clientes as $cliente)
              <option value="{{ $cliente->cliente_id }}">
                {{ $cliente->nombre }} - {{ $cliente->direccion }}
              </option>
           @endforeach
         </select>
      </div>

      <div class="mb-3">
         <label for="expiration_date" class="form-label">Fecha de Expiración:</label>
         <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
      </div>

      <div class="mb-3">
         <label for="payment_conditions" class="form-label">Condiciones de Pago:</label>
         <textarea name="payment_conditions" id="payment_conditions" class="form-control" rows="3"></textarea>
      </div>

      <div class="mb-3">
         <label for="additional_notes" class="form-label">Comentarios Adicionales:</label>
         <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
      </div>

      <hr>
      <h3>Productos (Ítems)</h3>

      <!-- Sección para ingresar un producto -->
      <div class="card mb-3">
        <div class="card-header">Agregar Producto</div>
        <div class="card-body">
          <!-- Fila 1: Producto, Cantidad, Precio, Botón -->
          <div class="row g-3">
            <div class="col-md-3">
              <label for="productSelect" class="form-label">Producto (Modelo)</label>
              <!-- Se mantiene la clase custom-select-no-arrow para el estilo -->
              <select id="productSelect" class="form-control custom-select-no-arrow">
                <option value="" disabled selected>-- Seleccione un producto --</option>
                @foreach($products as $prod)
                  <option value="{{ $prod->producto_id }}"
                          data-model="{{ $prod->modelo }}"
                          data-description="{{ $prod->descripcion }}"
                          data-price="{{ number_format($prod->precio, 2, '.', '') }}">
                    {{ $prod->modelo }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="productQuantity" class="form-label">Cantidad</label>
              <input type="number" id="productQuantity" class="form-control" value="1" min="1">
            </div>
            <div class="col-md-3">
              <label for="productPrice" class="form-label">Precio Unitario</label>
              <input type="number" step="0.01" id="productPrice" class="form-control" value="0.00" min="0">
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button type="button" class="btn btn-primary w-100" id="addProductEntry">Añadir Producto</button>
            </div>
          </div>
          <!-- Fila 2: Descripción -->
          <div class="row g-3 mt-3">
            <div class="col-12">
              <label for="productDescription" class="form-label">Descripción</label>
              <textarea id="productDescription" class="form-control" rows="2"></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Vista en tarjetas de productos cotizados -->
      <div class="card mb-3">
        <div class="card-header">Productos Cotizados</div>
        <div class="card-body">
          <!-- Aquí se mostrarán las tarjetas de cada producto -->
          <div id="itemsContainer" class="row">
            <!-- Se llenará dinámicamente con JS -->
          </div>
        </div>
      </div>

      <!-- Campo oculto para enviar el JSON de productos -->
      <input type="hidden" name="products_data" id="products_data">

      <!-- Descuento global -->
      <div class="mb-3">
         <label for="discount_percentage" class="form-label">Descuento (%)</label>
         <input type="number" name="discount_percentage" id="discount_percentage" class="form-control"
                value="0" min="0" max="100" step="0.01">
      </div>

      <!-- Totales -->
      <div class="mb-3">
         <label class="form-label">Subtotal:</label>
         <span id="subtotalDisplay">0.00</span>
      </div>
      <div class="mb-3">
         <label class="form-label">Total (con descuento):</label>
         <span id="totalDisplay">0.00</span>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Cotización</button>
    </form>
</div>
@stop

@section('css')
<!-- Estilos personalizados para el select -->
<style>
  /* Quitar la flecha nativa de algunos navegadores */
  .custom-select-no-arrow {
    -moz-appearance: none;
    -webkit-appearance: none;
    appearance: none;
    background-color: #fff;
    padding-right: 2.2rem;
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    background-image: url("data:image/svg+xml,%3Csvg fill='none' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5H7z' fill='%23666'/%3E%3C/svg%3E");
  }
  .custom-select-no-arrow::-ms-expand {
    display: none;
  }
</style>
<!-- CSS de Select2 (versión 4.0.13) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
<!-- JS de Select2 (versión 4.0.13); se usa el jQuery de AdminLTE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    // Inicializar Select2 en el select de productos con el campo de búsqueda activado
    $('#productSelect').select2({
        placeholder: "-- Seleccione un producto --",
        width: '100%',
        minimumResultsForSearch: 0
    });

    // Array para almacenar los productos agregados
    let productsAdded = [];

    // Actualizar descripción y precio cuando se selecciona un producto
    $('#productSelect').on('change', function() {
        let selectedOption = $(this).find(':selected');
        let defaultDescription = selectedOption.data('description') || "";
        let defaultPrice = parseFloat(selectedOption.data('price')) || 0;
        if ( parseFloat($('#productPrice').val()) === 0 ) {
            $('#productPrice').val(defaultPrice.toFixed(2));
        }
        $('#productDescription').val(defaultDescription);
    });

    // Función para actualizar la vista de productos y los totales
    function updateProductsView() {
        $('#itemsContainer').empty();
        let subtotal = 0;
        $.each(productsAdded, function(index, item) {
            let lineTotal = item.quantity * item.unit_price;
            subtotal += lineTotal;
            let itemHtml = `
              <div class="col-12 mb-3">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>${item.model}</strong>
                    <button type="button" class="btn btn-danger btn-sm" data-index="${index}">
                      Eliminar
                    </button>
                  </div>
                  <div class="card-body">
                    <p class="mb-1"><em>${item.description}</em></p>
                    <p class="mb-1">
                      <span class="fw-bold">Cantidad:</span> ${item.quantity}<br>
                      <span class="fw-bold">Precio Unitario:</span> ${item.unit_price.toFixed(2)}<br>
                      <span class="fw-bold">Total:</span> ${lineTotal.toFixed(2)}
                    </p>
                  </div>
                </div>
              </div>
            `;
            $('#itemsContainer').append(itemHtml);
        });
        let discount = parseFloat($('#discount_percentage').val()) || 0;
        let totalAfterDiscount = subtotal * (1 - discount / 100);
        $('#subtotalDisplay').text(subtotal.toFixed(2));
        $('#totalDisplay').text(totalAfterDiscount.toFixed(2));
        $('#products_data').val(JSON.stringify(productsAdded));
    }

    // Agregar producto al arreglo y actualizar vista
    $('#addProductEntry').on('click', function() {
        let prodId = $('#productSelect').val();
        if (!prodId) {
            alert('Seleccione un producto.');
            return;
        }
        let selectedOption = $('#productSelect').find(':selected');
        let model = selectedOption.data('model') || "";
        let description = $('#productDescription').val().trim();
        let quantity = parseFloat($('#productQuantity').val()) || 0;
        let unit_price = parseFloat($('#productPrice').val()) || 0;
        if (quantity <= 0 || unit_price < 0) {
            alert('Ingrese valores válidos en cantidad y precio.');
            return;
        }
        productsAdded.push({
            product_id: prodId,
            model: model,
            description: description,
            quantity: quantity,
            unit_price: unit_price
        });
        // Reiniciar campos y restablecer Select2
        $('#productSelect').val(null).trigger('change');
        $('#productQuantity').val("1");
        $('#productPrice').val("0.00");
        $('#productDescription').val("");
        updateProductsView();
    });

    // Eliminar producto mediante delegación
    $('#itemsContainer').on('click', 'button.btn-danger', function() {
        let index = $(this).data('index');
        productsAdded.splice(index, 1);
        updateProductsView();
    });

    // Actualizar totales al cambiar el descuento
    $('#discount_percentage').on('input', function() {
        updateProductsView();
    });
});
</script>
@stop
