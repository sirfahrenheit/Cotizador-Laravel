<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Distribuidora Jadi') }}</title>

        <!-- Favicon con el logo de la empresa -->
        <link rel="shortcut icon" href="{{ asset('images/mi-logo.jpg') }}" type="image/png">

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts y estilos -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Preloader: pantalla de carga con el logo */
            #preloader {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 9999;
                width: 100%;
                height: 100%;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    </head>
    <body class="hold-transition sidebar-mini {{ session('dark_mode', false) ? 'dark-mode' : '' }}">
        <!-- Preloader -->
        <div id="preloader">
            <img src="{{ asset('images/mi-logo.jpg') }}" alt="{{ config('app.name', 'Distribuidora Jadi') }} Logo" class="max-w-xs">
        </div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts -->
        <script>
            // Oculta el preloader cuando la página termina de cargar
            window.addEventListener('load', function() {
                var preloader = document.getElementById('preloader');
                preloader.style.display = 'none';
            });
        </script>
    </body>
</html>

