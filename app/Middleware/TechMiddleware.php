<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechMiddleware
{
    /**
     * Maneja una solicitud entrante.
     *
     * Permite el acceso solo si el usuario está autenticado y tiene rol "técnico".
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && strtolower(Auth::user()->role) === 'tecnico') {
            return $next($request);
        }
        abort(403, 'Acceso no autorizado. Esta sección es solo para técnicos.');
    }
}
