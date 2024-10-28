<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Receita;
use App\Models\Tipo;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar os tipos de categorias principais para o sub menu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Buscar as 4 notícias mais recentes
        $noticias = Noticia::latest()->take(4)->get();

        // Buscar as 4 receitas mais recentes
        $receitas = Receita::latest()->take(4)->get();

        // Buscar as categorias de nível "marca"
        $marcas = Categoria::where('nivel', 'marca')->get();

        // Passar os dados para a view
        return view('home', compact('tiposHeader', 'noticias', 'receitas', 'marcas'));
    }
}
