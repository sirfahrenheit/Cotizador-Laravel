<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Distribuidora Jadi') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- Estilos personalizados para auto-ocultar el navbar -->
  <style>
    /* Agregamos transición para el navbar */
    #mainNavbar {
      transition: transform 0.3s ease-in-out;
    }
    /* Clase para ocultar el navbar moviéndolo hacia arriba */
    .navbar-hidden {
      transform: translateY(-100%);
    }
  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
  <div class="wrapper">
    <!-- Navbar: aquí incluimos un id para identificarlo en JS -->
    <nav id="mainNavbar" class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Izquierda: menú hamburguesa y enlaces -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
      </ul>
      <!-- Derecha: usuario y dropdown -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            {{ Auth::user()->name }} <i class="fas fa-angle-down"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Perfil
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/mi-logo.png') }}" alt="{{ config('app.name', 'Distribuidora Jadi') }} Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Distribuidora Jadi') }}</span>
      </a>
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
              <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <!-- Agrega más enlaces según lo necesites -->
          </ul>
        </nav>
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contenido de la página -->
    <div class="content-wrapper">
      <!-- Opcional: header de contenido -->
      @if(isset($header))
        <section class="content-header">
          <div class="container-fluid">
            {{ $header }}
          </div>
        </section>
      @endif

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          {{ $slot }}
        </div>
      </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; {{ date('Y') }}
        <a href="#">{{ config('app.name', 'Distribuidora Jadi') }}</a>.
      </strong>
      Todos los derechos reservados.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
  @stack('scripts')

  <!-- Script para auto-ocultar el navbar al hacer scroll -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let lastScrollTop = 0;
      const navbar = document.getElementById('mainNavbar');
      
      window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop && scrollTop > 50) {
          // Si el usuario baja (y ha superado 50px), oculta el navbar
          navbar.classList.add('navbar-hidden');
        } else {
          // Si sube, muestra el navbar
          navbar.classList.remove('navbar-hidden');
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
      });
    });
  </script>
</body>
</html>