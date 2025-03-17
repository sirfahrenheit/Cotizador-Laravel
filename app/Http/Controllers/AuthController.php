<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra la vista de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Realiza el inicio de sesión de un usuario.
     *
     * Este método utiliza el facade Auth para autenticar al usuario a partir de las credenciales recibidas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validar la solicitud
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Regenerar la sesión para evitar ataques de fijación de sesión
            $request->session()->regenerate();

            // Redirigir según el rol del usuario (ajusta las rutas según tu lógica)
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin');
            } elseif ($user->role === 'tecnico') {
                return redirect()->route('dashboard.tecnico');
            } elseif ($user->role === 'mecanico') {
                return redirect()->route('dashboard.mecanico');
            } else {
                return redirect()->route('dashboard');
            }
        }

        // Si falla la autenticación, redirige de vuelta con un mensaje de error
        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos.',
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar la sesión y regenerar el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
