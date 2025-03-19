<!-- resources/views/auth/login.blade.php -->
<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Dirección de Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" 
                          class="block mt-1 w-full" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus 
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <!-- Contenedor relativo para ubicar el icono dentro -->
            <div class="relative">
                <!-- Ajustamos el padding derecho para que no se superponga el icono con el texto -->
                <x-text-input id="password"
                              class="block mt-1 w-full pr-10" 
                              type="password" 
                              name="password" 
                              required 
                              autocomplete="current-password" />
                <!-- Botón / icono para mostrar/ocultar -->
                <button type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500"
                        onclick="togglePasswordVisibility()">
                    <!-- Icono para mostrar la contraseña -->
                    <svg id="show-password-icon" 
                         class="h-5 w-5" 
                         fill="none" 
                         stroke-linecap="round" 
                         stroke-linejoin="round" 
                         stroke-width="2"
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7-5.065 7-9.542 7-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Icono para ocultar la contraseña (inicialmente oculto) -->
                    <svg id="hide-password-icon" 
                         class="h-5 w-5 hidden" 
                         fill="none" 
                         stroke-linecap="round" 
                         stroke-linejoin="round" 
                         stroke-width="2"
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.462-4.275" />
                        <path d="M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Recordar contraseña -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Botón de enviar y enlace de recuperar contraseña -->
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 mr-3" 
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const showIcon = document.getElementById('show-password-icon');
            const hideIcon = document.getElementById('hide-password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                hideIcon.classList.add('hidden');
                showIcon.classList.remove('hidden');
            }
        }
    </script>
</x-guest-layout>


