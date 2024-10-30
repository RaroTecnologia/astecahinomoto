<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Receita;
use App\Models\Tipo;
use App\Models\Categoria;
use App\Models\HomeBanner;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar banners ordenados
        $banners = HomeBanner::orderBy('ordem')->get();

        // Buscar os tipos de categorias principais para o sub menu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Buscar as 4 notícias mais recentes
        $noticias = Noticia::latest()->take(4)->get();

        // Buscar as 4 receitas mais recentes com todos os campos necessários
        $receitas = Receita::select('id', 'nome', 'chamada', 'imagem')
            ->latest()
            ->take(4)
            ->get();

        // Buscar as categorias de nível "marca"
        $marcas = Categoria::where('nivel', 'marca')->get();

        // Passar os dados para a view
        return view('home', compact('banners', 'tiposHeader', 'noticias', 'receitas', 'marcas'));
    }
}
