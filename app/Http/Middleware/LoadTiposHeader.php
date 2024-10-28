<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tipo;

class LoadTiposHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Compartilhar a variável com todas as views
        view()->share('tiposHeader', $tiposHeader);

        return $next($request);
    }
}
