<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Noticia;
use App\Models\Sku;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'produtos');

        if ($type === 'produtos') {
            return Sku::with([
                'produto',
                'produto.categoria',
                'produto.categoria.parent'
            ])
                ->whereHas('produto', function ($q) use ($query) {
                    $q->where('nome', 'like', "%{$query}%")
                        ->orWhere('descricao', 'like', "%{$query}%")
                        ->where('is_active', true);
                })
                ->where('is_active', true)
                ->take(10)
                ->get()
                ->map(function ($sku) {
                    // Encontra a categoria marca (parent da parent)
                    $categoriaMarca = $sku->produto->categoria->parent;
                    while ($categoriaMarca && $categoriaMarca->nivel !== 'marca') {
                        $categoriaMarca = $categoriaMarca->parent;
                    }

                    return [
                        'id' => $sku->id,
                        'nome' => $sku->produto->nome,
                        'slug' => $sku->produto->slug,
                        'categoria' => [
                            'nome' => $sku->produto->categoria->nome,
                            'slug' => $categoriaMarca ? $categoriaMarca->slug : null
                        ]
                    ];
                })
                ->filter(function ($item) {
                    return !is_null($item['categoria']['slug']); // Remove itens sem marca
                });
        }

        try {
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
