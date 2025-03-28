<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nueva Actividad</title>
</head>
<body>
    <h1>Nueva Actividad Registrada</h1>
    <p>Se ha registrado una actividad de tipo: <strong>{{ $actividad->tipo }}</strong>.</p>
    <p>Fecha: {{ $actividad->fecha }}</p>
    <p>Descripción: {{ $actividad->descripcion ?? 'Sin descripción' }}</p>
</body>
</html>
