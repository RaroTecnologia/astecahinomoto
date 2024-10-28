<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Sku;
use App\Models\Tipo;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Carregar todas as categorias do tipo "produto" para filtros
        $categorias = Categoria::where('tipo', 'produto')->get();

        // Carregar todas as marcas (categorias de nível "marca")
        $marcas = Categoria::where('nivel', 'marca')->whereNull('parent_id')->get();

        // Filtros de marca, produto e linha na requisição
        $marcaFiltro = $request->input('marca');
        $produtoFiltro = $request->input('produto');
        $linhaFiltro = $request->input('linha');

        // Definir produtos com base no filtro de marca
        $produtos = collect(); // Inicializa como coleção vazia
        if ($marcaFiltro) {
            // Obter a categoria da marca selecionada
            $marcaSelecionada = Categoria::where('id', $marcaFiltro)->where('nivel', 'marca')->first();

            if ($marcaSelecionada) {
                // Carregar produtos da marca filtrando por `parent_id`
                $produtos = Categoria::where('parent_id', $marcaSelecionada->id)
                    ->where('nivel', 'produto')
                    ->get();
            }
        }

        // Definir linhas com base no filtro de produto
        $linhas = $produtoFiltro ? Categoria::where('parent_id', $produtoFiltro)->where('nivel', 'linha')->get() : collect();

        // Consulta de SKUs com filtros de marca, produto e linha
        $skusQuery = Sku::with(['produto.categoria']);

        // Aplicar filtro de marca nos SKUs
        if ($marcaFiltro && isset($marcaSelecionada)) {
            $skusQuery->whereHas('produto', function ($query) use ($marcaSelecionada) {
                $query->where('categoria_id', $marcaSelecionada->id);
            });
        }

        // Aplicar filtro de produto nos SKUs
        if ($produtoFiltro) {
            $skusQuery->whereHas('produto', function ($query) use ($produtoFiltro) {
                $query->where('categoria_id', $produtoFiltro);
            });
        }

        // Aplicar filtro de linha nos SKUs
        if ($linhaFiltro) {
            $skusQuery->whereHas('produto', function ($query) use ($linhaFiltro) {
                $query->where('categoria_id', $linhaFiltro);
            });
        }

        // Obter todos os SKUs filtrados
        $skus = $skusQuery->get();

        // Agrupar os SKUs pela marca correta (percorrendo as categorias até a marca)
        $skusPorMarca = $skus->groupBy(function ($sku) {
            $marca = $sku->produto->categoria->getMarca();
            return $marca ? $marca->nome : 'Sem Marca';
        });

        return view('catalogo', compact('skusPorMarca', 'categorias', 'marcas', 'produtos', 'linhas', 'marcaFiltro', 'produtoFiltro', 'linhaFiltro', 'tiposHeader'));
    }
}
