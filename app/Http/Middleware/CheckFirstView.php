<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('credentials')) {
            // Si los campos no están presentes, redirige a la primera vista
            return redirect()->route('login')->with('error', 'Primero debes inciar session.');
        }

        return $next($request);
    }
}
