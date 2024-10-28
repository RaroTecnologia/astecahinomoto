<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado e se é admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Redireciona o usuário se não for admin
        return redirect('/dashboard')->withErrors('Acesso negado: você não tem permissão para acessar esta área.');
    }
}
