<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Categoria;
use App\Models\Tipo;
use Illuminate\Http\Request;

class ReceitaController extends Controller
{
    public function index()
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Paginação para otimizar a listagem de receitas
        $receitas = Receita::paginate(12);

        // Buscar apenas as categorias com tipo "receita"
        $categorias = Categoria::where('tipo', 'receita')->get();

        // Retornar a view com as receitas e as categorias filtradas
        return view('receitas.index', compact('receitas', 'categorias', 'tiposHeader'));
    }


    public function show($categoriaSlug, $slug)
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Buscar a categoria
        $categoria = Categoria::where('slug', $categoriaSlug)->firstOrFail();

        // Buscar a receita pelo slug e categoria
        $receita = Receita::where('slug', $slug)->where('categoria_id', $categoria->id)->firstOrFail();

        // Buscar sugestões de receitas
        $sugestoes = Receita::where('categoria_id', $categoria->id)
            ->where('id', '!=', $receita->id) // Exclui a receita atual
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Retornar a view com a receita, categoria e sugestões
        return view('receitas.show', compact('receita', 'categoria', 'sugestoes', 'tiposHeader'));
    }


    public function categoriasReceitas($slug)
    {
        // Buscar a categoria pelo slug
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        // Buscar todas as receitas dessa categoria
        $receitas = Receita::where('categoria_id', $categoria->id)->paginate(10);

        // Retornar a view com as receitas da categoria
        return view('categorias.receitas', compact('categoria', 'receitas'));
    }

    public function filtrarPorCategoria($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();
        $receitas = Receita::where('categoria_id', $categoria->id)->paginate(8);

        return response()->json([
            'receitas' => view('partials._receitas-list', compact('receitas'))->render(),
            'pagination' => view('partials._pagination', compact('receitas'))->render(),
            'count' => $receitas->total()
        ]);
    }
}
