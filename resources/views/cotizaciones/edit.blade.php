@extends('adminlte::page')

@section('title', 'Editar Cotización')

@section('content_header')
    <h1>Editar Cotización #{{ $cotizacion->cotizacion_numero }}</h1>
@stop

@section('content')
<div class="container">
    @if(session('success'))
       <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('cotizaciones.update', $cotizacion->cotizacion_id) }}" method="POST" id="cotizacionForm">
      @csrf
      @method('PATCH')

      <!-- Datos generales -->
      <div class="mb-3">
         <label for="cliente_id" class="form-label">Cliente:</label>
         <select name="cliente_id" id="cliente_id" class="form-control" required>
           <option value="">Seleccione un cliente</option>
           @foreach($clientes as $cliente)
              <option value="{{ $cliente->cliente_id }}" {{ $cliente->cliente_id == $cotizacion->cliente_id ? 'selected' : '' }}>
                {{ $cliente->nombre }} - {{ $cliente->direccion }}
              </option>
           @endforeach
         </select>
      </div>
      <div class="mb-3">
         <label for="expiration_date" class="form-label">Fecha de Expiración:</label>
         <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{ $cotizacion->expiration_date }}" required>
      </div>
      <div class="mb-3">
         <label for="payment_conditions" class="form-label">Condiciones de Pago:</label>
         <textarea name="payment_conditions" id="payment_conditions" class="form-control" rows="3">{{ $cotizacion->payment_conditions }}</textarea>
      </div>
      <div class="mb-3">
         <label for="additional_notes" class="form-label">Comentarios Adicionales:</label>
         <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3">{{ $cotizacion->additional_notes }}</textarea>
      </div>

      <hr>
      <h3>Productos (Ítems)</h3>

      <!-- Formulario único para agregar/actualizar producto -->
      <div class="card mb-3" id="productForm">
        <div class="card-header">Producto</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label for="productSelect" class="form-label">Producto (Modelo)</label>
              <div class="custom-select-wrapper">
                <select id="productSelect" class="custom-select-no-arrow">
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
            <div class="col-12 d-flex justify-content-end">
              <button type="button" class="btn btn-secondary me-2" id="addProductBtn">Agregar Producto</button>
              <button type="button" class="btn btn-secondary me-2" id="updateProductBtn" style="display:none;">Actualizar Producto</button>
              <button type="button" class="btn btn-light" id="cancelEditBtn" style="display:none;">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Listado de productos agregados en formato tarjetas -->
      <div class="card mb-3">
        <div class="card-header">Listado de Productos</div>
        <div class="card-body">
          <div id="cardsContainer" class="row">
            <!-- Se llenará dinámicamente con JavaScript -->
          </div>
        </div>
      </div>

      <!-- Campo oculto para enviar el JSON de productos -->
      <input type="hidden" name="products_data" id="products_data">

      <!-- Descuento global y totales -->
      <div class="mb-3">
         <label for="discount_percentage" class="form-label">Descuento (%)</label>
         <input type="number" name="discount_percentage" id="discount_percentage" class="form-control"
                value="{{ $cotizacion->discount_percentage ?? 0 }}" min="0" max="100" step="0.01">
      </div>
      <div class="mb-3">
         <label class="form-label">Subtotal:</label>
         <span id="subtotalDisplay">0.00</span>
      </div>
      <div class="mb-3">
         <label class="form-label">Total (con descuento):</label>
         <span id="totalDisplay">0.00</span>
      </div>

      <button type="submit" class="btn btn-primary">Actualizar Cotización</button>
    </form>
</div>
@stop

@section('css')
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <style>
    /* Contenedor para el select personalizado */
    .custom-select-wrapper {
      position: relative;
      display: inline-block;
      width: 100%;
    }
    .custom-select-no-arrow {
      width: 100%;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      border: 1px solid #ced4da;
      border-radius: 4px;
      padding: 0.375rem 2.25rem 0.375rem 0.75rem;
      font-size: 1rem;
      background-color: #fff;
    }
    .custom-select-wrapper::after {
      content: "\25BE";
      position: absolute;
      top: 50%;
      right: 0.75rem;
      transform: translateY(-50%);
      pointer-events: none;
      color: #6c757d;
      font-size: 0.8rem;
    }
    .custom-select-no-arrow::-ms-expand {
      display: none;
    }
    /* Estilos para las tarjetas del listado */
    #cardsContainer .card-item {
      margin-bottom: 1rem;
    }
    .card-item .card-header {
      padding: 0.5rem 1rem;
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-item .item-model {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: calc(100% - 120px);
      margin: 0;
    }
    .card-item .card-actions {
      flex-shrink: 0;
      display: flex;
      gap: 0.5rem;
    }
    .card-item .card-body {
      padding: 1rem;
    }
    /* Ajustar Select2 para integrarlo con el estilo del select */
    .select2-container--default .select2-selection--single {
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      border: 1px solid #ced4da;
      border-radius: 4px;
      background-color: #fff;
    }
  </style>
  <!-- CSS de Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
<!-- Se carga Select2 (se usa el jQuery de AdminLTE) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 en el select de productos para búsqueda
    $('#productSelect').select2({
        placeholder: "-- Seleccione un producto --",
        width: '100%',
        minimumResultsForSearch: 0
    });

    // Arreglo que contendrá los productos agregados
    let productsAdded = [];
    // Variable para saber si se está editando un producto (índice en el arreglo)
    let editIndex = null;

    // Referencias del formulario único para producto
    const productSelect = document.getElementById('productSelect');
    const productQuantity = document.getElementById('productQuantity');
    const productPrice = document.getElementById('productPrice');
    const productDescription = document.getElementById('productDescription');
    const addProductBtn = document.getElementById('addProductBtn');
    const updateProductBtn = document.getElementById('updateProductBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    // Referencias del listado de tarjetas y totales
    const cardsContainer = document.getElementById('cardsContainer');
    const discountInput = document.getElementById('discount_percentage');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const productsDataInput = document.getElementById('products_data');

    // Lista de productos disponibles (desde el controlador)
    const productsList = @json($products);
    // Ítems precargados de la cotización
    let existingItems = {!! $itemsJson ?? '[]' !!};

    // Si existen ítems precargados, se asignan al arreglo
    if(existingItems && existingItems.length > 0) {
        productsAdded = existingItems;
    }

    // Función para reiniciar el formulario de producto
    function resetProductForm() {
        productSelect.value = "";
        $(productSelect).trigger('change'); // Para que Select2 actualice
        productQuantity.value = "1";
        productPrice.value = "0.00";
        productDescription.value = "";
        editIndex = null;
        addProductBtn.style.display = "inline-block";
        updateProductBtn.style.display = "none";
        cancelEditBtn.style.display = "none";
    }

    // Función para actualizar el listado de tarjetas y totales
    function updateProductsView() {
        cardsContainer.innerHTML = "";
        let subtotal = 0;

        productsAdded.forEach((item, index) => {
            const lineTotal = item.quantity * item.unit_price;
            subtotal += lineTotal;

            const cardItem = document.createElement('div');
            cardItem.classList.add('col-12', 'col-md-6', 'card-item');

            cardItem.innerHTML = `
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="item-model">${item.model}</p>
                        <div class="card-actions">
                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-index="${index}">Editar</button>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-index="${index}">Eliminar</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><strong>Descripción:</strong> ${item.description}</p>
                        <p><strong>Cantidad:</strong> ${item.quantity}</p>
                        <p><strong>Precio Unitario:</strong> ${parseFloat(item.unit_price).toFixed(2)}</p>
                        <p><strong>Total:</strong> ${lineTotal.toFixed(2)}</p>
                    </div>
                </div>
            `;
            cardsContainer.appendChild(cardItem);
        });

        let discount = parseFloat(discountInput.value) || 0;
        let totalAfterDiscount = subtotal * (1 - discount / 100);
        subtotalDisplay.textContent = subtotal.toFixed(2);
        totalDisplay.textContent = totalAfterDiscount.toFixed(2);
        productsDataInput.value = JSON.stringify(productsAdded);
    }

    // Función para agregar un nuevo producto al listado
    function addProduct() {
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

        productsAdded.push({
            product_id: prodId,
            model: model,
            description: description,
            quantity: quantity,
            unit_price: unit_price
        });
        updateProductsView();
        resetProductForm();
    }

    // Función para actualizar un producto existente en el listado
    function updateProduct() {
        if (editIndex === null) return;
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

        productsAdded[editIndex] = {
            product_id: prodId,
            model: model,
            description: description,
            quantity: quantity,
            unit_price: unit_price
        };
        updateProductsView();
        resetProductForm();
    }

    // Al cambiar el select, se carga la descripción y el precio por defecto
    $('#productSelect').on('change', function() {
        let selectedOption = $(this).find(':selected');
        let defaultDescription = selectedOption.data('description') || "";
        let defaultPrice = parseFloat(selectedOption.data('price')) || 0;
        if ( parseFloat($('#productPrice').val()) === 0 ) {
            $('#productPrice').val(defaultPrice.toFixed(2));
        }
        $('#productDescription').val(defaultDescription);
    });

    // Eventos de los botones del formulario de producto
    addProductBtn.addEventListener('click', addProduct);
    updateProductBtn.addEventListener('click', updateProduct);
    cancelEditBtn.addEventListener('click', resetProductForm);

    // Delegación de eventos en el listado de tarjetas
    cardsContainer.addEventListener('click', function(e) {
        const target = e.target;
        const index = target.getAttribute('data-index');
        if (target.classList.contains('edit-btn')) {
            const item = productsAdded[index];
            productSelect.value = item.product_id;
            $(productSelect).trigger('change');
            productQuantity.value = item.quantity;
            productPrice.value = parseFloat(item.unit_price).toFixed(2);
            productDescription.value = item.description;
            editIndex = parseInt(index);
            addProductBtn.style.display = "none";
            updateProductBtn.style.display = "inline-block";
            cancelEditBtn.style.display = "inline-block";
            // Desplazar el formulario hacia arriba (PC y móvil)
            document.getElementById('productForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else if (target.classList.contains('delete-btn')) {
            productsAdded.splice(index, 1);
            updateProductsView();
            if (editIndex == index) {
                resetProductForm();
            }
        }
    });

    // Actualizar totales al cambiar el descuento
    discountInput.addEventListener('input', updateProductsView);

    // Inicializar: actualizar la vista con los ítems precargados (si existen)
    updateProductsView();
    resetProductForm();
});
</script>
@stop




