<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Backup Exitoso</title>
</head>
<body>
    <h1>Backup de Base de Datos Exitoso</h1>
    <p>El backup se ha realizado correctamente.</p>
    <p><strong>Archivo generado:</strong> {{ $filename }}</p>
    <p><strong>Ubicación:</strong> {{ $backupPath }}</p>
    <p><strong>Fecha y hora:</strong> {{ now()->toDateTimeString() }}</p>
</body>
</html>
