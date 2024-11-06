<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Receita;
use App\Models\Tipo;
use App\Models\Categoria;
use App\Models\HomeBanner;
use App\Models\HomeDestaque;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar banners ordenados
        $banners = HomeBanner::orderBy('ordem')->get();

        // Buscar produtos em destaque
        $destaques = HomeDestaque::with('produto')
            ->orderBy('ordem')
            ->get();

        // Buscar os tipos de categorias principais para o sub menu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Buscar as 4 notícias mais recentes
        $noticias = Noticia::latest()->take(4)->get();

        // Buscar 4 receitas aleatórias com suas categorias
        $receitas = Receita::with('categoria')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Buscar as categorias de nível "marca"
        $marcas = Categoria::where('nivel', 'marca')->get();

        // Debug para verificar os dados
        foreach ($receitas as $receita) {
            Log::info('Receita: ' . $receita->nome);
            Log::info('Categoria ID: ' . $receita->categoria_id);
            Log::info('Categoria: ', $receita->categoria ? ['nome' => $receita->categoria->nome] : ['sem categoria']);
        }

        // Passar os dados para a view
        return view('home', compact('banners', 'tiposHeader', 'noticias', 'receitas', 'marcas', 'destaques'));
    }
}
