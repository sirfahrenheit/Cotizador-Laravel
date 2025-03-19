<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización {{ $numeroCotizacion }}</title>
</head>
<body>
<p>Estimado(a) {{ $clienteNombre }},</p>

<p>Espero que este mensaje le encuentre bien.</p>

<p>
    Adjunto a este correo la cotización <strong>{{ $numeroCotizacion }}</strong>
    correspondiente a los equipos y servicios cotizados.
</p>

<p>A continuación, encontrará un resumen de las condiciones generales:</p>

@if(!empty($paymentConditions))
    <p><strong>Condiciones de pago:</strong></p>
    <p>{!! nl2br(e($paymentConditions)) !!}</p>
@endif

@if(!empty($additionalNotes))
    <p><strong>Notas / Garantía:</strong></p>
    <p>{!! nl2br(e($additionalNotes)) !!}</p>
@endif

<p>
    Puede consultar los detalles de la cotización en el siguiente enlace:
    <a href="{{ $linkPublico }}" target="_blank">Ver Cotización</a>
</p>

<p>Quedamos a la espera de su confirmación.</p>

<p>
    Atentamente,<br>
    Bot de Distribuidora Jadi
</p>
</body>
</html>
