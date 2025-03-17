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
      <div class="form-group">
         <label for="cliente_id">Cliente:</label>
         <select name="cliente_id" id="cliente_id" class="form-control" required>
           <option value="">Seleccione un cliente</option>
           @foreach($clientes as $cliente)
              <option value="{{ $cliente->cliente_id }}">{{ $cliente->nombre }} - {{ $cliente->direccion }}</option>
           @endforeach
         </select>
      </div>

      <div class="form-group">
         <label for="expiration_date">Fecha de Expiración:</label>
         <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
      </div>

      <div class="form-group">
         <label for="payment_conditions">Condiciones de Pago:</label>
         <textarea name="payment_conditions" id="payment_conditions" class="form-control" rows="3"></textarea>
      </div>

      <div class="form-group">
         <label for="additional_notes">Comentarios Adicionales:</label>
         <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
      </div>

      <hr>
      <h3>Productos (Ítems)</h3>
      <!-- Contenedor dinámico para los ítems -->
      <div id="items"></div>
      <button type="button" class="btn btn-secondary mb-3" id="addItemBtn">Agregar Producto</button>

      <!-- Campo oculto para enviar el JSON de productos -->
      <input type="hidden" name="products_data" id="products_data">

      <!-- Descuento global -->
      <div class="form-group">
         <label for="discount_percentage">Descuento (%)</label>
         <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" value="0" min="0" max="100" step="0.01">
      </div>

      <!-- Totales -->
      <div class="form-group">
         <label>Subtotal:</label>
         <span id="subtotalDisplay">0.00</span>
      </div>
      <div class="form-group">
         <label>Total (con descuento):</label>
         <span id="totalDisplay">0.00</span>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Cotización</button>
    </form>
</div>
@stop

@section('css')
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const itemsDiv = document.getElementById('items');
    const addItemBtn = document.getElementById('addItemBtn');
    const discountInput = document.getElementById('discount_percentage');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const productsDataInput = document.getElementById('products_data');

    // Lista de productos disponibles (pasada desde el controlador)
    const productsList = @json($products);

    // Función para crear una fila de producto. Para create no hay item precargado, por eso el parámetro item es null.
    function createItemRow(item = null) {
        const row = document.createElement('div');
        row.classList.add('item-row');
        row.style.border = "1px solid #ccc";
        row.style.padding = "10px";
        row.style.marginBottom = "10px";
        row.innerHTML = `
            <div class="form-group">
                <label>Producto (Modelo)</label>
                <select class="form-control product-select" required>
                    <option value="">-- Seleccione un producto --</option>
                    ${
                        productsList.map(prod => {
                            // Para create, no hay item previo; se muestra solo el modelo.
                            return `<option value="${prod.producto_id}" data-model="${prod.modelo}" data-description="${prod.descripcion}" data-price="${parseFloat(prod.precio).toFixed(2)}">
                                        ${prod.modelo}
                                    </option>`;
                        }).join('')
                    }
                </select>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control item-description" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" class="form-control item-quantity" value="${ item ? item.quantity : 1 }" min="1" required>
            </div>
            <div class="form-group">
                <label>Precio Unitario (Editable)</label>
                <input type="number" step="0.01" class="form-control item-price" value="${ item ? parseFloat(item.unit_price).toFixed(2) : '0.00' }" min="0" required>
            </div>
            <button type="button" class="btn btn-danger remove-item-btn">Eliminar Producto</button>
        `;
        itemsDiv.appendChild(row);

        // Referencias y eventos
        const selectElem = row.querySelector('.product-select');
        const descriptionElem = row.querySelector('.item-description');
        const quantityElem = row.querySelector('.item-quantity');
        const priceElem = row.querySelector('.item-price');
        const removeBtn = row.querySelector('.remove-item-btn');

        selectElem.addEventListener('change', function() {
            const selectedOption = selectElem.options[selectElem.selectedIndex];
            const defaultPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            // Actualiza el precio unitario
            if (!priceElem.dataset.edited || parseFloat(priceElem.value) === 0) {
                priceElem.value = defaultPrice.toFixed(2);
            }
            // Actualiza la descripción en el campo dedicado
            descriptionElem.value = selectedOption.getAttribute('data-description') || "";
            updateProductsData();
        });

        // Actualizar totales al modificar cantidad o precio
        quantityElem.addEventListener('input', updateProductsData);
        priceElem.addEventListener('input', updateProductsData);
        // Marcar como editado cuando se modifica el precio manualmente
        priceElem.addEventListener('input', function(e) {
            e.target.dataset.edited = true;
        });
        removeBtn.addEventListener('click', function() {
            if(document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                updateProductsData();
            } else {
                alert("Debe haber al menos un producto.");
            }
        });

        // Si hay un item precargado (en edición) lo usamos para precargar la descripción
        if (item) {
            descriptionElem.value = item.description;
        }
        updateProductsData();
    }

    // Función para recorrer las filas y actualizar el JSON y totales
    function updateProductsData() {
        let items = [];
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(function(row) {
            const productSelect = row.querySelector('.product-select');
            const quantityElem = row.querySelector('.item-quantity');
            const priceElem = row.querySelector('.item-price');
            const descriptionElem = row.querySelector('.item-description');

            const productId = productSelect.value;
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const model = selectedOption ? selectedOption.getAttribute('data-model') : "";
            const description = descriptionElem.value;
            const quantity = parseFloat(quantityElem.value) || 0;
            const unitPrice = parseFloat(priceElem.value) || 0;
            const lineTotal = quantity * unitPrice;
            subtotal += lineTotal;
            if(productId && quantity > 0) {
                items.push({
                    product_id: productId,
                    model: model,
                    description: description,
                    quantity: quantity,
                    unit_price: unitPrice
                });
            }
        });
        productsDataInput.value = JSON.stringify(items);
        let discount = parseFloat(discountInput.value) || 0;
        let totalAfterDiscount = subtotal * (1 - discount / 100);
        subtotalDisplay.textContent = subtotal.toFixed(2);
        totalDisplay.textContent = totalAfterDiscount.toFixed(2);
    }

    addItemBtn.addEventListener('click', createItemRow);
    discountInput.addEventListener('input', updateProductsData);

    // Para crear, si no hay items previos, se agrega una fila por defecto
    createItemRow();
});
</script>
@stop
