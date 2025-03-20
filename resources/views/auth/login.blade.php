@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('title', 'Iniciar Sesión')

{{-- Aquí colocamos el HTML directamente en la sección, en lugar de pasarlo como cadena --}}
@section('auth_header')
    <h4 class="text-center mb-3">Bienvenido a Distribuidora Jadi</h4>
    <p class="text-center">Inicia sesión para continuar</p>
@endsection

@section('auth_body')
    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Campo para correo --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>

        {{-- Campo para contraseña con toggle de ver/ocultar --}}
        <div class="input-group mb-3">
            <input id="password" type="password" name="password" class="form-control" placeholder="Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <a href="#" id="togglePassword" style="color: inherit;"><span class="fas fa-eye"></span></a>
                </div>
            </div>
        </div>

        {{-- Recordar usuario --}}
        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recuérdame</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    <p class="mb-1">
        <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
    </p>
    <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">Registrar un nuevo usuario</a>
    </p>
@endsection

@section('css')
    <style>
        /* Personalización adicional para un look más moderno */
        .login-page {
            background: #f4f6f9;
        }
        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .input-group-text {
            background-color: #e9ecef;
            border: none;
        }
        .btn-primary {
            background-color: #0F4C75;
            border-color: #0F4C75;
        }
        .btn-primary:hover {
            background-color: #0d3f5d;
            border-color: #0d3f5d;
        }
    </style>
@endsection

@section('js')
    <script>
        // Toggle de mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            e.preventDefault();
            const passwordField = document.getElementById('password');
            const currentType = passwordField.getAttribute('type');
            const newType = currentType === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', newType);
            // Alternar el icono entre fa-eye y fa-eye-slash
            this.querySelector('span').classList.toggle('fa-eye');
            this.querySelector('span').classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
