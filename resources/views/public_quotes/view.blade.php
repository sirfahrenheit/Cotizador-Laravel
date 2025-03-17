<!DOCTYPE html>
<html lang="es">
<head>  
  <meta charset="UTF-8">  
  <title>Cotización #{{ $quote->cotizacion_numero }}</title>  
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <!-- Fuente Montserrat (opcional) -->  
  <link rel="preconnect" href="https://fonts.googleapis.com">  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">  
  <style>
    /* Reset básico */
    body, h1, h2, h3, p { margin: 0; padding: 0; }
    body {
      font-family: 'Montserrat', sans-serif;
      background: #eef2f7;
      color: #333;
    }
    .container {
      width: 90%;
      max-width: 900px;
      margin: 20px auto;
    }
    /* Encabezado */
    .header {
      text-align: center;
      padding: 20px 0;
    }
    .header img { max-height: 80px; }
    /* Banner superior */
    .banner {
      background: #5e2129;
      color: #fff;
      padding: 30px 20px;
      border-radius: 8px;
      text-align: center;
    }
    .banner h1 {
      font-size: 2em;
      margin-bottom: 10px;
    }
    .banner .meta {
      margin-bottom: 20px;
      font-size: 0.95em;
    }
    .info-wrapper {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      text-align: left;
      margin-top: 20px;
    }
    .info-block {
      flex: 1 1 45%;
      margin-bottom: 10px;
    }
    .info-block p { margin: 3px 0; }
    /* Comentarios */
    .comments {
      background:#5e2129;
      padding: 15px;
      border-radius: 4px;
      margin-top: 20px;
      font-style: italic;
      font-size: 0.95em;
    }
    /* Sección de ítems */
    .items-section {
      margin: 30px 0;
    }
    .items-section h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .item-card {
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      padding: 15px;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .item-info {
      flex: 1;
    }
    .item-info h3 {
      font-size: 1.1em;
      margin-bottom: 5px;
    }
    .item-info p {
      margin: 3px 0;
      color: #555;
    }
    .item-price {
      font-weight: bold;
      text-align: right;
      min-width: 120px;
    }
    /* Totales */
    .totals {
      background: #fff;
      padding: 15px;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      max-width: 400px;
      margin: 20px auto;
      text-align: right;
    }
    .totals p { margin: 5px 0; }
    .totals .totals-label { margin-right: 15px; }
    /* Condiciones de pago */
    .payment-conditions {
      background: #fff;
      padding: 15px;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin: 30px auto;
      max-width: 700px;
      font-size: 0.95em;
      line-height: 1.5em;
    }
    .payment-conditions h3 {
      margin-bottom: 10px;
    }
    /* Footer */
    .footer {
      background: #5e2129;
      color: #fff;
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      margin-top: 30px;
    }
    .footer p { margin: 5px 0; }
    .footer .btn {
      background: #000;
      border: none;
      padding: 10px 20px;
      color: #fff;
      border-radius: 4px;
      margin: 10px 5px 0 5px;
      cursor: pointer;
    }
    .footer .btn:hover {
      background: #3a5c8c;
    }
    @media print {
      .footer .btn {
        display: none;
      }
    }
  </style>
</head>
<body>  
  <div class="container my-5">
    <!-- Encabezado (logo) -->
    <div class="header">
      <img src="{{ $logoUrl }}" alt="Logo de Mi Empresa">
    </div>
    <!-- Banner principal con datos de la cotización -->
    <div class="banner">
      <h1>Cotización #{{ $quote->cotizacion_numero }}</h1>
      <div class="meta">
        <p>Creada el {{ $creationDateFormatted }} &mdash; Vence: {{ $expirationDateFormatted }}</p>
      </div>
      <div class="info-wrapper">
        <div class="info-block">
          <p><strong>Cliente:</strong> {{ $quote->client->nombre ?? 'N/A' }}</p>
          <p><strong>Dirección:</strong> {{ $quote->client->direccion ?? 'No especificada' }}</p>
          <p><strong>Teléfono:</strong> {{ $quote->client->telefono ?? 'No especificado' }}</p>
          <p><strong>Email:</strong> {{ $quote->client->correo ?? 'No especificado' }}</p>
        </div>
        <div class="info-block">
          <p><strong>Responsable:</strong> {{ $quote->user->name ?? 'N/A' }}</p>
          <p><strong>Email:</strong> {{ $quote->user->email ?? 'No especificado' }}</p>
        </div>
      </div>
      <!-- Comentarios -->
      <div class="comments">
        <h3>Comentarios</h3>
        <p>{{ $quote->additional_notes ?? 'Sin comentarios' }}</p>
      </div>
    </div>
    <!-- Sección de ítems -->
    <div class="items-section">
      <h2>Productos y servicios</h2>
      @foreach($quote->items as $item)
        <div class="item-card">
          <div class="item-info">
            <h3>{{ $item->description }}</h3>
            <p><strong>Modelo:</strong> {{ $item->modelo }}</p>
            <p><strong>Cantidad:</strong> {{ $item->quantity }}</p>
            <p><strong>Descuento aplicado:</strong> {{ $discountPercentage }}%</p>
          </div>
          <div class="item-price">
            {{ $item->quantity }} x Q{{ number_format($item->unit_price, 2) }}<br>
            <span style="font-size: 0.9em;">Total: Q{{ number_format($item->total_price, 2) }}</span>
          </div>
        </div>
      @endforeach
    </div>
    <!-- Totales -->
    <div class="totals">
      <p><span class="totals-label">Subtotal:</span> Q{{ number_format($quote->subtotal, 2) }}</p>
      <p><span class="totals-label">Descuento:</span> Q{{ number_format($quote->discount, 2) }}</p>
      <p style="font-weight:bold;"><span class="totals-label">Total:</span> Q{{ number_format($quote->total, 2) }}</p>
    </div>
    <!-- Condiciones de pago -->
    <div class="payment-conditions">
      <h3>Condiciones de pago</h3>
      <p>{{ $quote->payment_conditions ?? 'No especificadas' }}</p>
    </div>
    <!-- Footer -->
    <div class="footer">
      <p><strong>¿Tienes preguntas? Contáctanos</strong></p>
      <p>{{ $quote->user->name ?? '' }} - {{ $quote->user->email ?? '' }}</p>
      <button class="btn" onclick="downloadPdf()">Descargar PDF</button>
      <button class="btn" onclick="window.print()">Imprimir</button>
    </div>
  </div>
  
  <script>
    function downloadPdf() {
      window.location.href = "{{ route('quotes.pdf', ['token' => $quote->cotizacion_token]) }}";
    }
  </script>
</body>
</html>
