<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Sku;
use App\Models\Tipo;
use App\Models\Categoria;
use App\Models\ValorNutricional;

class ProdutoController extends Controller
{
    public function show($slugMarca, $slugProduto)
    {
        // Carrega a categoria da marca principal
        $categoriaMarca = Categoria::where('slug', $slugMarca)
            ->where('nivel', 'marca')
            ->firstOrFail();

        // Busca todos os IDs de subcategorias recursivamente
        $categoriaIds = $this->getAllSubcategoryIds($categoriaMarca->id);
        $categoriaIds[] = $categoriaMarca->id; // Inclui o ID da marca principal

        // Tenta encontrar o produto com base no slug e na lista de IDs de categorias
        $produto = Produto::where('slug', $slugProduto)
            ->whereIn('categoria_id', $categoriaIds)
            ->where('is_active', true)
            ->firstOrFail();

        // Carrega valores nutricionais se houver
        $valoresNutricionais = ValorNutricional::where('tabela_nutricional_id', $produto->tabela_nutricional_id)
            ->join('nutrientes', 'valores_nutricionais.nutriente_id', '=', 'nutrientes.id')
            ->select('nutrientes.nome', 'valores_nutricionais.valor_por_100g', 'valores_nutricionais.valor_por_porção', 'valores_nutricionais.valor_diario')
            ->get();

        // Carrega os SKUs do produto
        $skus = Sku::where('produto_id', $produto->id)
            ->where('is_active', true)
            ->get();

        // Busca produtos relacionados dentro da mesma categoria
        $produtosRelacionados = Produto::where('categoria_id', $produto->categoria_id)
            ->where('id', '!=', $produto->id)
            ->where('is_active', true)
            ->get();

        // Carrega os tipos de header
        $tiposHeader = Tipo::orderBy('ordem')->get();

        $categoriaProduto = $produto;

        // Retorna a view com as variáveis necessárias
        return view('produtos.show', compact(
            'produto',
            'categoriaMarca',
            'tiposHeader',
            'valoresNutricionais',
            'produtosRelacionados',
            'categoriaProduto',
            'skus' // Adiciona a variável de SKUs à view
        ));
    }

    /**
     * Método recursivo para buscar todas as subcategorias de uma categoria.
     */
    private function getAllSubcategoryIds($parentId)
    {
        $subcategorias = Categoria::where('parent_id', $parentId)->pluck('id')->toArray();

        foreach ($subcategorias as $subcategoriaId) {
            $subcategorias = array_merge($subcategorias, $this->getAllSubcategoryIds($subcategoriaId));
        }

        return $subcategorias;
    }
}
