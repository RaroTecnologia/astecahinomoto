<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita; // Supondo que você tenha modelos para cada tipo de conteúdo
use App\Models\Noticia;
use App\Models\Produto;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type'); // O tipo de busca: receitas, noticias, produtos

        switch ($type) {
            case 'receitas':
                $results = Receita::where('nome', 'like', "%{$query}%")
                    ->orWhere('ingredientes', 'like', "%{$query}%")
                    ->with('categoria') // Certifique-se de que o relacionamento com categoria está sendo carregado
                    ->take(5)
                    ->get();
                break;

            default:
                $results = collect(); // Retornar coleção vazia se não encontrar o tipo
        }

        return response()->json($results);
    }
}
