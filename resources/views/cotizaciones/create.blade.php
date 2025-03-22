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
          <div class="row g-3">
            <div class="col-md-4">
              <label for="productSelect" class="form-label">Producto (Modelo)</label>
              <!-- Notar la clase extra "custom-select-no-arrow" -->
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
            <div class="col-md-4">
              <label for="productQuantity" class="form-label">Cantidad</label>
              <input type="number" id="productQuantity" class="form-control" value="1" min="1">
            </div>
            <div class="col-md-4">
              <label for="productPrice" class="form-label">Precio Unitario</label>
              <input type="number" step="0.01" id="productPrice" class="form-control" value="0.00" min="0">
            </div>
            <div class="col-12">
              <label for="productDescription" class="form-label">Descripción</label>
              <textarea id="productDescription" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-12">
              <button type="button" class="btn btn-secondary" id="addProductEntry">Añadir Producto</button>
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
    background-color: #fff; /* Ajustar si quieres otro color de fondo */
    /* Agrega un poco de padding para que no se encime el texto con la flecha */
    padding-right: 2.2rem; 
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    /* Opcional: añadir una flechita personalizada con un SVG */
    background-image: url("data:image/svg+xml,%3Csvg fill='none' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5H7z' fill='%23666'/%3E%3C/svg%3E");
  }
  /* Para IE y Edge viejos */
  .custom-select-no-arrow::-ms-expand {
    display: none;
  }
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos
    const productSelect = document.getElementById('productSelect');
    const productQuantity = document.getElementById('productQuantity');
    const productPrice = document.getElementById('productPrice');
    const productDescription = document.getElementById('productDescription');
    const addProductEntry = document.getElementById('addProductEntry');

    const itemsContainer = document.getElementById('itemsContainer'); 
    const discountInput = document.getElementById('discount_percentage');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const productsDataInput = document.getElementById('products_data');

    // Array para almacenar los productos agregados
    let productsAdded = [];

    // Cuando se cambia el producto, se actualiza la descripción y el precio por defecto
    productSelect.addEventListener('change', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (!selectedOption) return;

        const defaultDescription = selectedOption.getAttribute('data-description') || "";
        const defaultPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

        // Solo actualizar si el precio está en 0
        if (parseFloat(productPrice.value) === 0){
          productPrice.value = defaultPrice.toFixed(2);
        }
        productDescription.value = defaultDescription;
    });

    // Función para actualizar la vista de productos y los totales
    function updateProductsView() {
        itemsContainer.innerHTML = '';
        let subtotal = 0;

        productsAdded.forEach((item, index) => {
            const lineTotal = item.quantity * item.unit_price;
            subtotal += lineTotal;

            // Crear el contenedor (card) para cada producto
            const itemDiv = document.createElement('div');
            // Para mostrar en una sola columna en teléfono, usamos col-12
            itemDiv.classList.add('col-12', 'mb-3');

            itemDiv.innerHTML = `
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
            `;
            itemsContainer.appendChild(itemDiv);
        });

        // Calcular totales
        let discount = parseFloat(discountInput.value) || 0;
        let totalAfterDiscount = subtotal * (1 - discount / 100);

        // Mostrar en pantalla
        subtotalDisplay.textContent = subtotal.toFixed(2);
        totalDisplay.textContent = totalAfterDiscount.toFixed(2);

        // Actualizar campo oculto con JSON
        productsDataInput.value = JSON.stringify(productsAdded);
    }

    // Añadir producto al arreglo
    addProductEntry.addEventListener('click', function() {
        const prodId = productSelect.value;
        if (!prodId) {
            alert('Seleccione un producto.');
            return;
        }
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const model = selectedOption.getAttribute('data-model') || "";
        const description = productDescription.value.trim();
        const quantity = parseFloat(productQuantity.value) || 0;
        const unit_price = parseFloat(productPrice.value) || 0;

        if (quantity <= 0 || unit_price < 0) {
            alert('Ingrese valores válidos en cantidad y precio.');
            return;
        }

        // Agregar al array
        productsAdded.push({
            product_id: prodId,
            model: model,
            description: description,
            quantity: quantity,
            unit_price: unit_price
        });

        // Limpiar campos
        productSelect.value = "";
        productQuantity.value = "1";
        productPrice.value = "0.00";
        productDescription.value = "";

        // Actualizar la vista
        updateProductsView();
    });

    // Eliminar producto (delegación de evento en el contenedor)
    itemsContainer.addEventListener('click', function(e) {
        if(e.target && e.target.matches('button.btn-danger')) {
            const index = e.target.getAttribute('data-index');
            productsAdded.splice(index, 1);
            updateProductsView();
        }
    });

    // Recalcular totales al cambiar el descuento
    discountInput.addEventListener('input', updateProductsView);
});
</script>
@stop
