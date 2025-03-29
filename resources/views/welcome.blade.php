<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bienvenido a Cotizador-Laravel</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Fuente Montserrat desde Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-700 via-purple-700 to-pink-700 text-white">
  <!-- Header -->
  <header class="py-8 text-center">
    <h1 class="text-5xl font-bold">Cotizador-Laravel</h1>
    <p class="mt-4 text-xl">Gestiona tus cotizaciones y ventas de forma inteligente</p>
  </header>

  <!-- Main Hero Section -->
  <main class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-xl shadow-xl p-10 max-w-xl text-center">
      <h2 class="text-4xl font-bold mb-6">Bienvenido</h2>
      <p class="text-lg mb-8">
        Descubre una interfaz limpia y moderna para administrar tus cotizaciones, clientes y ventas. 
        Optimiza tus decisiones con una experiencia de usuario excepcional.
      </p>
      <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-white text-blue-600 font-bold rounded-full shadow-lg hover:bg-opacity-90 transition duration-300">
        Iniciar Sesión
      </a>
    </div>
  </main>

  <!-- Footer -->
  <footer class="py-4 text-center">
    <p class="text-sm">&copy; {{ date('Y') }} Cotizador-Laravel. Todos los derechos reservados.</p>
  </footer>
</body>
</html>

