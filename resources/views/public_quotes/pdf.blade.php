<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $quote->cotizacion_numero }}</title>
    <style>
        /* Configuración de la página (hoja oficio) */
        @page {
            margin: 2cm;
            size: legal;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        /* Encabezado con logo */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            max-height: 50px; /* Logo más pequeño */
            display: inline-block;
        }
        /* Banner principal */
        .banner {
            background: #5e2129;
            color: #fff;
            padding: 10px 15px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .banner h1 {
            margin: 0;
            font-size: 1.5em;
        }
        .meta {
            margin-top: 3px;
            font-size: 0.85em;
        }
        /* Secciones */
        .section {
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            page-break-inside: avoid;
        }
        .section h2 {
            margin-bottom: 5px;
            color: #5e2129;
            border-bottom: 1px solid #5e2129;
            padding-bottom: 3px;
            font-size: 1.2em;
        }
        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            page-break-inside: avoid;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
            font-size: 0.95em;
        }
        th {
            background-color: #5e2129;
            color: #fff;
        }
        /* Totales */
        .totals {
            text-align: right;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .totals p {
            margin: 3px 0;
            font-size: 1em;
        }
        /* Pie de Página */
        .footer {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background-color: #5e2129;
            color: #fff;
            border-radius: 4px;
            font-size: 12px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/mi-logo.jpg') }}" alt="Logo">
    </div>
    <div class="banner">
        <h1>Cotización #{{ $quote->cotizacion_numero }}</h1>
        <div class="meta">
            <p>Creada el {{ $creationDateFormatted }} &mdash; Vence: {{ $expirationDateFormatted }}</p>
        </div>
    </div>
    <div class="section">
        <h2>Datos del Cliente</h2>
        <p><strong>Nombre:</strong> {{ $quote->client->nombre }}</p>
        <p><strong>Dirección:</strong> {{ $quote->client->direccion }}</p>
        <p><strong>Teléfono:</strong> {{ $quote->client->telefono }}</p>
        <p><strong>Correo:</strong> {{ $quote->client->correo }}</p>
    </div>
    <div class="section">
        <h2>Detalles de la Cotización</h2>
        <p><strong>Condiciones de Pago:</strong> {{ $quote->payment_conditions }}</p>
        <p><strong>Comentarios:</strong> {{ $quote->additional_notes }}</p>
    </div>
    <div class="section">
        <h2>Productos y Servicios</h2>
        @if($quote->items->isEmpty())
            <p>No se encontraron ítems.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Modelo</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->modelo }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Q{{ number_format($item->unit_price, 2) }}</td>
                            <td>Q{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="totals">
        <p><strong>Subtotal:</strong> Q{{ number_format($subtotal, 2) }}</p>
        <p><strong>Descuento:</strong> Q{{ number_format($descuentoTotal, 2) }}</p>
        <p><strong>Total:</strong> Q{{ number_format($total, 2) }}</p>
    </div>
    <div class="footer">
        <p>Distribuidora Jadi</p>
        <p>Tel: 24584142</p>
        <p>Email: {{ $quote->user->correo ?? $quote->user->email }}</p>
    </div>
</body>
</html>
