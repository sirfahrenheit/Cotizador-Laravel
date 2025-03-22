<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso Jadi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 desde CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  
  <!-- Fuente Google: Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(135deg, #00adb5 0%, #ffffff 100%);
      min-height: 100vh;
    }
    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 15px;
    }
    .login-card {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 2.5rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 1s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .logo-container {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .logo-container img {
      max-width: 150px;
    }
    .login-card h1 {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
      color: #333;
    }
    .form-label {
      font-size: 1rem;
      color: #555;
    }
    .form-control {
      font-size: 1rem;
      padding: 0.75rem;
      border-radius: 5px;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 173, 181, 0.25);
      border-color: #00adb5;
    }
    .btn-primary {
      background-color: #00adb5;
      border: none;
      font-size: 1rem;
      padding: 0.75rem;
      border-radius: 5px;
    }
    .btn-primary:hover {
      background-color: #00bcc5;
    }
    .form-check-label {
      font-size: 0.95rem;
      color: #555;
    }
    /* Ajuste en el input-group para mostrar/ocultar contraseña */
    .input-group .form-control {
      border-right: none;
    }
    .toggle-password-btn {
      border: 1px solid #ced4da;
      border-left: none;
      background-color: #fff;
      color: #6c757d;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.375rem 0.75rem;
    }
    .toggle-password-btn:hover {
      background-color: #f0f0f0;
    }
    @media (max-width: 576px) {
      .login-card {
        padding: 2rem;
        max-width: 90%;
      }
      .login-card h1 {
        font-size: 1.6rem;
      }
      .form-label,
      .form-check-label {
        font-size: 0.95rem;
      }
      .form-control {
        font-size: 0.95rem;
      }
      .btn-primary {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      
      <!-- Contenedor del logo -->
      <div class="logo-container">
        <img src="{{ asset('images/mi-logo.png') }}" alt="Logo Jadi">
      </div>
      
      <h1>Acceso Jadi</h1>
      
      <!-- Formulario de Login -->
      <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <!-- Correo Electrónico -->
        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input id="email" type="email"
                 class="form-control @error('email') is-invalid @enderror"
                 name="email" value="{{ old('email') }}"
                 required autocomplete="email" autofocus>
          @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        
        <!-- Contraseña con botón para mostrar/ocultar -->
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <div class="input-group">
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            <button class="btn toggle-password-btn" type="button" id="btnTogglePassword">
              <!-- Icono inicial: ojo -->
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                <path d="M8 10.5A2.5 2.5 0 1 0 8 5.5a2.5 2.5 0 0 0 0 5z"/>
              </svg>
            </button>
          </div>
          @error('password')
            <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        
        <!-- Recordar sesión -->
        <div class="mb-3 form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember"
                 {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember">Recuérdame</label>
        </div>
        
        <!-- Botón de Acceder -->
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Acceder</button>
        </div>
        
        <!-- Enlace para recuperación de contraseña (opcional) -->
        @if (Route::has('password.request'))
          <div class="text-center">
            <a class="text-decoration-none" href="{{ route('password.request') }}">
              ¿Olvidaste tu contraseña?
            </a>
          </div>
        @endif
      </form>
    </div>
  </div>
  
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Script para mostrar/ocultar contraseña con íconos SVG inline -->
  <script>
    const btnTogglePassword = document.getElementById('btnTogglePassword');
    const passwordInput = document.getElementById('password');
    
    // Definir los íconos SVG inline
    const iconEye = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
      <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
      <path d="M8 10.5A2.5 2.5 0 1 0 8 5.5a2.5 2.5 0 0 0 0 5z"/>
    </svg>`;
    const iconEyeSlash = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
      <path d="M13.359 11.238l1.387 1.386a.5.5 0 0 1-.708.708l-1.387-1.386a8.018 8.018 0 0 1-3.65 1.115c-4.418 0-8-4.5-8-4.5a15.48 15.48 0 0 1 2.263-2.812L.354 2.354a.5.5 0 1 1 .708-.708l14 14a.5.5 0 0 1-.708.708l-2.116-2.116zM11.753 10.04l-1.394-1.393a2.5 2.5 0 0 0-3.098-3.098L7.25 4.252A4.5 4.5 0 0 1 11.753 10.04zM9.622 11.813l-.829-.829A3.5 3.5 0 0 1 4.187 8c0-.47.102-.917.288-1.322l-.708-.708C3.202 7.243 3 7.598 3 8c0 1.538 2.5 3.5 5 3.5.403 0 .756-.202 1.021-.497l-.399-.39z"/>
    </svg>`;
    
    btnTogglePassword.addEventListener('click', () => {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        btnTogglePassword.innerHTML = iconEyeSlash;
      } else {
        passwordInput.type = 'password';
        btnTogglePassword.innerHTML = iconEye;
      }
    });
  </script>
</body>
</html>

