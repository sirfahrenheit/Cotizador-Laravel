<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Maneja la solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario está autenticado y su rol es 'admin'
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta sección (solo Admin).');
        }

        return $next($request);
    }
}
