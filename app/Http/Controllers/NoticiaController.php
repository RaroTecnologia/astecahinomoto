<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Categoria;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        // Pegar todas as categorias do tipo notícias
        $categorias = Categoria::where('tipo', 'noticia')
            ->orderBy('nome')
            ->get();

        // Query base
        $query = Noticia::with('categoria');

        // Filtrar por categoria se especificado
        if ($request->has('categoria')) {
            $categoria = Categoria::where('slug', $request->categoria)
                ->where('tipo', 'noticia')
                ->first();

            if ($categoria) {
                $query->where('categoria_id', $categoria->id);
            }
        }

        // Ordenação
        switch ($request->get('order')) {
            case 'recent':
                $query->latest();
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest(); // Ordenação padrão
        }

        // Executar a query com paginação
        $noticias = $query->paginate(12);

        // Carregar tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Se for uma requisição AJAX, retornar JSON
        if ($request->ajax()) {
            $view = View::make('noticias._lista', compact('noticias'))->render();
            return response()->json([
                'list' => $view,
                'pagination' => $noticias->render()
            ]);
        }

        return view('noticias.index', compact('noticias', 'categorias', 'tiposHeader'));
    }

    public function show($categoria, $slug)
    {
        // Buscar a categoria
        $categoria = Categoria::where('slug', $categoria)
            ->where('tipo', 'noticia')
            ->firstOrFail();

        // Buscar a notícia e incrementar visualizações
        $noticia = Noticia::where('slug', $slug)
            ->where('categoria_id', $categoria->id)
            ->firstOrFail();

        // Incrementa visualizações
        $noticia->increment('views');

        // Notícias relacionadas (mesma categoria, excluindo atual)
        $relacionadas = Noticia::where('categoria_id', $categoria->id)
            ->where('id', '!=', $noticia->id)
            ->latest()
            ->take(4)
            ->get();

        // Carregar tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        return view('noticias.show', compact('noticia', 'categoria', 'relacionadas', 'tiposHeader'));
    }
}
