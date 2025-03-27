<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\FcmToken;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentica al usuario
        $request->authenticate();

        // Regenera la sesión para evitar session fixation
        $request->session()->regenerate();

        // Solo guarda el token FCM si tiene un valor no vacío
        if ($request->filled('fcm_token')) {
            FcmToken::updateOrCreate(
                ['token' => $request->input('fcm_token')],
                ['user_id' => Auth::id()]
            );
        } else {
            \Log::warning('No se recibió token FCM durante el login');
        }

        // Redirige al dashboard (o a donde desees)
        return redirect('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

