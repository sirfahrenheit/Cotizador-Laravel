<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Maneja la petición y verifica si el usuario es administrador.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            // Redirige a un lugar (por ejemplo, al dashboard) o muestra un error
            return redirect('/dashboard')->withErrors('No tienes permiso para acceder a esta sección.');
        }
        return $next($request);
    }
}
