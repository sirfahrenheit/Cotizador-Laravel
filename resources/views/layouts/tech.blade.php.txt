<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Técnico')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Puedes incluir Bootstrap minimal si lo deseas -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        /* Estilos simples para un layout técnico */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        header, footer {
            background: #5e2129;
            color: #fff;
            padding: 10px 20px;
        }
        header h1, footer p {
            margin: 0;
        }
        .content {
            margin-top: 20px;
        }
    </style>
    @yield('css')
</head>
<body>
    <header>
        <h1>Dashboard Técnico</h1>
    </header>

    <div class="content">
        @yield('content')
    </div>

    <footer class="text-center mt-4">
        <p>Distribuidora Jadi &mdash; Tel: 24584142 &mdash; Email: {{ Auth::user()->correo ?? Auth::user()->email }}</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @yield('js')
</body>
</html>
