<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Retorna as subcategorias de uma categoria pai via AJAX.
     */
    public function getSubcategorias($slug)
    {
        // Encontra a categoria pai pelo slug
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        // Obtém as subcategorias relacionadas à categoria pai
        $subcategorias = Categoria::where('parent_id', $categoria->id)->get();

        // Retorna as subcategorias em formato JSON para a requisição AJAX
        return response()->json(['subcategorias' => $subcategorias]);
    }
}
