<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Noticia;
use App\Models\Sku;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

    public function globalSearch(Request $request)
    {
        try {
            $query = $request->input('query');

            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }

            // Busca em produtos
            $produtos = Produto::with(['categoria.parent'])
                ->where('nome', 'like', "%{$query}%")
                ->orWhere('descricao', 'like', "%{$query}%")
                ->where('is_active', true)
                ->take(5)
                ->get()
                ->map(function ($produto) {
                    $categoriaMarca = $produto->categoria->parent;
                    while ($categoriaMarca && $categoriaMarca->nivel !== 'marca') {
                        $categoriaMarca = $categoriaMarca->parent;
                    }

                    // Verifique se $categoriaMarca não é nulo antes de acessar 'slug'
                    $slugMarca = $categoriaMarca ? $categoriaMarca->slug : 'default-slug';

                    return [
                        'title' => $produto->nome,
                        'description' => $produto->descricao,
                        'url' => route('produtos.show', ['slugMarca' => $slugMarca, 'slugProduto' => $produto->slug]),
                        'type' => 'Produto'
                    ];
                });

            // Busca em notícias
            $noticias = Noticia::where('titulo', 'LIKE', '%' . $query . '%')
                ->orWhere('conteudo', 'LIKE', '%' . $query . '%')
                ->with('categoria')
                ->take(5)
                ->get()
                ->map(function ($noticia) {
                    return [
                        'title' => $noticia->titulo,
                        'description' => Str::limit($noticia->conteudo, 100),
                        'url' => route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]),
                        'type' => 'Notícia'
                    ];
                });

            // Busca em receitas
            $receitas = Receita::where('nome', 'like', "%{$query}%")
                ->orWhere('chamada', 'like', "%{$query}%")
                ->with('categoria')
                ->take(5)
                ->get()
                ->map(function ($receita) {
                    return [
                        'title' => $receita->nome,
                        'description' => $receita->chamada,
                        'url' => route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]),
                        'type' => 'Receita'
                    ];
                });

            // Combina todos os resultados
            $results = $produtos->merge($noticias)->merge($receitas);

            return response()->json($results);
        } catch (\Exception $e) {
            Log::error('Erro na busca global: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno no servidor'], 500);
        }
    }
}
