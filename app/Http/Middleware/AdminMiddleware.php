<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!Auth::user()->hasRole('admin')) {
            return redirect('/dashboard')->with('error', 'Acesso negado: você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
