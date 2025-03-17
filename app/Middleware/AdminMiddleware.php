<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            return redirect('/dashboard')->withErrors('No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
