<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Categoria;
use App\Models\Tipo;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    // Listagem de notícias
    public function index()
    {
        // Pegar todas as categorias de notícias
        $categorias = Categoria::where('tipo', 'noticias')->get();

        // Pegar todas as notícias com paginação
        $noticias = Noticia::paginate(8);

        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        return view('noticias.index', compact('noticias', 'categorias', 'tiposHeader'));
    }

    // Exibir notícias por categoria
    public function categoriasNoticias($slug)
    {
        // Buscar a categoria
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        // Filtrar notícias pela categoria
        $noticias = Noticia::where('categoria_id', $categoria->id)->paginate(8);

        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        return view('noticias.index', compact('noticias', 'categoria', 'tiposHeader'));
    }

    // Exibir notícia individual
    public function show($categoria, $slug)
    {
        // Buscar a categoria
        $categoria = Categoria::where('slug', $categoria)->firstOrFail();

        // Buscar a notícia pelo slug
        $noticia = Noticia::where('slug', $slug)->where('categoria_id', $categoria->id)->firstOrFail();

        // Notícias relacionadas (baseado na mesma categoria)
        $relacionadas = Noticia::where('categoria_id', $categoria->id)
            ->where('id', '!=', $noticia->id) // Exclui a notícia atual
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        return view('noticias.show', compact('noticia', 'categoria', 'relacionadas', 'tiposHeader'));
    }
}
