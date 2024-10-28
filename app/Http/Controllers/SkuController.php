<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use App\Models\Categoria;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    /**
     * Lista os SKUs com base na categoria ou subcategoria.
     * @param string $categoriaSlug
     * @param string|null $subcategoriaSlug (opcional)
     */
    public function index($categoriaSlug, $subcategoriaSlug = null)
    {
        // Obter a categoria pelo slug
        $categoria = Categoria::where('slug', $categoriaSlug)->firstOrFail();

        // Se houver uma subcategoria, filtrar pelos SKUs da subcategoria
        if ($subcategoriaSlug) {
            $subcategoria = Categoria::where('slug', $subcategoriaSlug)
                ->where('parent_id', $categoria->id)
                ->firstOrFail();

            // Filtrar os SKUs pela subcategoria
            $skus = Sku::whereHas('produto', function ($query) use ($subcategoria) {
                $query->where('categoria_id', $subcategoria->id);
            })->paginate(12);
        } else {
            // Filtrar os SKUs pela categoria principal
            $skus = Sku::whereHas('produto', function ($query) use ($categoria) {
                $query->where('categoria_id', $categoria->id);
            })->paginate(12);
        }

        // Renderizar a página com os SKUs e a categoria (e subcategoria, se aplicável)
        return view('skus.index', compact('skus', 'categoria', 'subcategoriaSlug'));
    }

    /**
     * Exibe os detalhes de um SKU específico.
     * @param string $categoriaSlug
     * @param string|null $subcategoriaSlug (opcional)
     * @param string $skuSlug
     */
    public function show($categoriaSlug, $subcategoriaSlug = null, $skuSlug)
    {
        // Obter a categoria
        $categoria = Categoria::where('slug', $categoriaSlug)->firstOrFail();

        // Se houver uma subcategoria, obtenha-a
        if ($subcategoriaSlug) {
            $subcategoria = Categoria::where('slug', $subcategoriaSlug)
                ->where('parent_id', $categoria->id)
                ->firstOrFail();
        }

        // Buscar o SKU pelo slug
        $sku = Sku::where('slug', $skuSlug)->with('produto')->firstOrFail();

        // Renderizar a página de detalhes do SKU
        return view('skus.show', compact('sku', 'categoria', 'subcategoriaSlug'));
    }
}
