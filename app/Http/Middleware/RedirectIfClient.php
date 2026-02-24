<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->hasRole('merchant') || auth()->user()->hasRole('user'))) {
            return redirect()->route('dashboard')->with('error', 'Acceso Denegado: Esta sección es exclusiva para administración.');
        }
        return $next($request);
    }
}
