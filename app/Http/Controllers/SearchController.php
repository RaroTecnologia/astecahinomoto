<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Noticia;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            $type = $request->get('type');

            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }

            if ($type === 'noticias') {
                $results = Noticia::query()
                    ->where(function ($q) use ($query) {
                        $q->where('titulo', 'LIKE', '%' . $query . '%')
                            ->orWhere('conteudo', 'LIKE', '%' . $query . '%');
                    })
                    ->with(['categoria' => function ($q) {
                        $q->select('id', 'nome', 'slug');
                    }])
                    ->select('id', 'titulo', 'slug', 'categoria_id')
                    ->take(5)
                    ->get();

                return response()->json($results);
            }

            if ($type === 'receitas') {
                $results = Receita::where('nome', 'like', "%{$query}%")
                    ->orWhere('chamada', 'like', "%{$query}%")
                    ->with('categoria')
                    ->take(5)
                    ->get();

                return response()->json($results);
            }

            return response()->json([]);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }
}
