<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgeVerificationMiddleware
{
    protected $excludedPaths = [
        'verificar-idade',
        'acesso-negado',
        'api/verify-age'
    ];

    public function handle(Request $request, Closure $next)
    {
        // Verifica se o path atual está na lista de exceções
        if (in_array($request->path(), $this->excludedPaths)) {
            return $next($request);
        }

        // Se for uma requisição AJAX, permite passar
        if ($request->ajax()) {
            return $next($request);
        }

        // Verifica o cookie de idade
        if (!$request->cookie('age_verified')) {
            return redirect()->route('verificar-idade');
        }

        return $next($request);
    }
} 