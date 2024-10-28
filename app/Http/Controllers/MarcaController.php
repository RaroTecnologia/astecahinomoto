<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Models\Categoria;
use App\Models\Produto;

class MarcaController extends Controller
{
    // 1. Listar todos os tipos
    public function listarTipos()
    {
        $tiposHeader = Tipo::orderBy('ordem')->get();
        $tipos = Tipo::all();
        return view('marcas.tipos', compact('tipos', 'tiposHeader'));
    }

    // 2. Listar todas as marcas para um tipo específico
    public function listarMarcasPorTipo($tipoSlug)
    {
        $tiposHeader = Tipo::orderBy('ordem')->get();
        $tipo = Tipo::where('slug', $tipoSlug)->firstOrFail();
        $marcas = Categoria::where('tipo_id', $tipo->id)->where('nivel', 'marca')->get();

        return view('marcas.marcas-por-tipo', compact('tipo', 'marcas', 'tiposHeader'));
    }

    public function listarProdutosOuLinhasDaMarca($tipoSlug, $slugMarca)
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        $tipo = Tipo::where('slug', $tipoSlug)->firstOrFail();
        $marca = Categoria::where('slug', $slugMarca)->where('nivel', 'marca')->firstOrFail();

        // Verificar se a marca possui produtos diretos ou linhas
        $produtos = Categoria::where('parent_id', $marca->id)->where('nivel', 'produto')->get();
        $linhas = Categoria::where('parent_id', $marca->id)->where('nivel', 'linha')->get();

        if ($produtos->isNotEmpty()) {
            return view('marcas.produtos-marca', compact('tipo', 'marca', 'produtos', 'tiposHeader'));
        } elseif ($linhas->isNotEmpty()) {
            return view('marcas.linhas-marca', compact('tipo', 'marca', 'linhas', 'tiposHeader'));
        }

        abort(404, "Nenhum produto ou linha encontrado para esta marca.");
    }

    public function listarLinhasOuProdutos($tipoSlug, $slugMarca, $slugProduto, $slugLinha = null)
    {
        $tiposHeader = Tipo::orderBy('ordem')->get();
        $tipo = Tipo::where('slug', $tipoSlug)->firstOrFail();
        $marca = Categoria::where('slug', $slugMarca)->where('nivel', 'marca')->firstOrFail();
        $produtoOuLinha = Categoria::where('slug', $slugProduto)->where('parent_id', $marca->id)->firstOrFail();

        if ($slugLinha) {
            $linha = Categoria::where('slug', $slugLinha)->where('parent_id', $produtoOuLinha->id)->firstOrFail();
            $produtosFinais = Produto::where('categoria_id', $linha->id)->get();
        } else {
            $subLinhas = Categoria::where('parent_id', $produtoOuLinha->id)->where('nivel', 'linha')->get();
            $produtosFinais = Produto::where('categoria_id', $produtoOuLinha->id)->get();
        }

        // Verificar se há apenas um produto final
        if ($produtosFinais->count() === 1) {
            // Redirecionar diretamente para o único produto final encontrado
            $produtoUnico = $produtosFinais->first();
            return redirect()->route('produtos.show', ['slugMarca' => $marca->slug, 'slugProduto' => $produtoUnico->slug]);
        }

        if ($slugLinha) {
            if ($produtosFinais->isNotEmpty()) {
                return view('marcas.produtos-finais', compact(
                    'tipo',
                    'marca',
                    'produtoOuLinha',
                    'linha',
                    'produtosFinais',
                    'tiposHeader'
                ));
            }
        } else {
            if ($subLinhas->isNotEmpty()) {
                return view('marcas.linhas-produto', compact(
                    'tipo',
                    'marca',
                    'produtoOuLinha',
                    'subLinhas',
                    'tiposHeader'
                ));
            } elseif ($produtosFinais->isNotEmpty()) {
                $linha = null;
                return view('marcas.produtos-finais', compact(
                    'tipo',
                    'marca',
                    'produtoOuLinha',
                    'linha',
                    'produtosFinais',
                    'tiposHeader'
                ));
            }
        }

        abort(404, "Nenhuma linha ou produto final encontrado.");
    }
}
