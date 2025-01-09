<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Sku;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // Carregar tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Carregar todas as categorias do tipo "produto" para filtros
        $categorias = Categoria::where('tipo', 'produto')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Carregar todas as marcas
        $marcas = Categoria::where('nivel', 'marca')
            ->whereNull('parent_id')
            ->get();

        // Carregar SKUs iniciais
        $skus = Sku::with(['produto.categoria'])
            ->whereHas('produto', function($query) {
                $query->whereHas('categoria');
            })
            ->paginate(24);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.catalogo.produtos-grid', compact('skus'))->render(),
                'pagination' => view('vendor.pagination.custom', ['paginator' => $skus])->render()
            ]);
        }

        return view('catalogo', compact('categorias', 'marcas', 'skus', 'tiposHeader'));
    }

    public function filtrar(Request $request)
    {
        try {
            Log::info('Iniciando filtro com parâmetros:', $request->all());

            $skusQuery = Sku::with([
                'produto',
                'produto.categoria',
                'produto.categoria.parent'
            ])
                ->where('skus.is_active', 1)
                ->whereHas('produto', function ($query) {
                    $query->where('produtos.is_active', 1);
                });

            // Filtro de Marca
            if ($marca = $request->input('marca')) {
                $skusQuery->whereHas('produto.categoria', function ($query) use ($marca) {
                    $query->where(function ($q) use ($marca) {
                        // Produtos diretamente na marca
                        $q->where('categorias.id', $marca)
                            // Produtos em subcategorias da marca
                            ->orWhere('categorias.parent_id', $marca)
                            // Produtos em linhas das subcategorias
                            ->orWhereHas('parent', function ($p) use ($marca) {
                                $p->where('parent_id', $marca);
                            });
                    });
                });
            }

            // Filtro de Produto (só aplica se tiver produto selecionado)
            if ($produto = $request->input('produto')) {
                $skusQuery->whereHas('produto.categoria', function ($query) use ($produto) {
                    $query->where(function ($q) use ($produto) {
                        $q->where('categorias.id', $produto)
                            ->orWhere('categorias.parent_id', $produto);
                    });
                });
            }

            // Filtro de Linha (só aplica se tiver linha selecionada)
            if ($linha = $request->input('linha')) {
                $skusQuery->whereHas('produto.categoria', function ($query) use ($linha) {
                    $query->where('categorias.id', $linha);
                });
            }

            // Log da query final para debug
            Log::info('Query final:', [
                'sql' => $skusQuery->toSql(),
                'bindings' => $skusQuery->getBindings()
            ]);

            $skus = $skusQuery->paginate(24);

            // Log dos resultados
            Log::info('Resultados encontrados:', [
                'total' => $skus->total(),
                'por_pagina' => $skus->perPage(),
                'pagina_atual' => $skus->currentPage()
            ]);

            return response()->json([
                'success' => true,
                'html' => view('partials.catalogo.produtos-grid', compact('skus'))->render(),
                'pagination' => view('vendor.pagination.custom', ['paginator' => $skus])->render()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao filtrar:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao filtrar produtos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubcategorias($id)
    {
        $query = Categoria::select('categorias.*')
            ->leftJoin('produtos', 'categorias.id', '=', 'produtos.categoria_id')
            ->leftJoin('skus', 'produtos.id', '=', 'skus.produto_id')
            ->where('categorias.tipo', 'produto')
            ->where(function ($q) {
                $q->whereNull('produtos.id')
                    ->orWhere('produtos.is_active', 1);
            })
            ->where(function ($q) {
                $q->whereNull('skus.id')
                    ->orWhere('skus.is_active', 1);
            });

        if ($id === 'all') {
            // Retorna todas as marcas (nível superior)
            $query->whereNull('parent_id');
        } else {
            // Retorna os produtos ou linhas da marca/produto selecionado
            $query->where('parent_id', $id);
        }

        return $query->groupBy('categorias.id')
            ->orderBy('categorias.nivel')
            ->orderBy('categorias.nome')
            ->get();
    }

    public function getCategorias(Request $request)
    {
        try {
            $tipo = $request->input('tipo', 'marca');
            $parentId = $request->input('parent_id');

            $query = Categoria::query()
                ->where('tipo', 'produto')
                ->where('nivel', $tipo);

            if ($parentId) {
                $query->where('parent_id', $parentId);
            } else {
                $query->whereNull('parent_id');
            }

            // Filtro específico baseado no tipo de categoria
            if ($tipo === 'marca') {
                // Marcas que tenham produtos ativos com SKUs ativos
                $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('produtos')
                        ->whereColumn('produtos.categoria_id', 'categorias.id')
                        ->where('produtos.is_active', 1)  // Primeiro verifica se o produto está ativo
                        ->whereExists(function ($subquery) {
                            $subquery->select(DB::raw(1))
                                ->from('skus')
                                ->whereColumn('skus.produto_id', 'produtos.id')
                                ->where('skus.is_active', 1);
                        });
                })
                    ->orWhereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('categorias as child')
                            ->whereColumn('child.parent_id', 'categorias.id')
                            ->whereExists(function ($subquery) {
                                $subquery->select(DB::raw(1))
                                    ->from('produtos')
                                    ->whereColumn('produtos.categoria_id', 'child.id')
                                    ->where('produtos.is_active', 1)  // Mesma verificação para subcategorias
                                    ->whereExists(function ($innerquery) {
                                        $innerquery->select(DB::raw(1))
                                            ->from('skus')
                                            ->whereColumn('skus.produto_id', 'produtos.id')
                                            ->where('skus.is_active', 1);
                                    });
                            });
                    });
            } elseif ($tipo === 'produto') {
                // Produtos que tenham SKUs ativos diretamente ou através de suas linhas
                $query->where(function ($query) {
                    $query->whereHas('produtos', function ($query) {
                        $query->where('is_active', 1)
                            ->whereHas('skus', function ($query) {
                                $query->where('is_active', 1);
                            });
                    });
                });
            } elseif ($tipo === 'linha') {
                // Linhas que tenham produtos ativos com SKUs ativos diretamente associados
                $query->whereHas('produtos', function ($query) {
                    $query->where('produtos.categoria_id', DB::raw('categorias.id'))
                        ->where('is_active', 1)
                        ->whereHas('skus', function ($query) {
                            $query->where('is_active', 1);
                        });
                });
            }

            Log::info('Query Categorias:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'tipo' => $tipo,
                'parent_id' => $parentId
            ]);

            $categorias = $query->orderBy('nome')->get();

            // Debug adicional
            Log::info('Categorias encontradas:', [
                'total' => $categorias->count(),
                'ids' => $categorias->pluck('id')->toArray(),
                'nomes' => $categorias->pluck('nome')->toArray()
            ]);

            return response()->json([
                'success' => true,
                'categorias' => $categorias->map(function ($categoria) {
                    return [
                        'id' => $categoria->id,
                        'nome' => $categoria->nome,
                        'imagem' => $categoria->imagem ? asset('storage/' . $categoria->imagem) : null
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar categorias:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar categorias: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProdutos($marca)
    {
        try {
            Log::info('Buscando produtos para marca:', ['marca_id' => $marca]);

            $produtos = Categoria::where('tipo', 'produto')
                ->where('nivel', 'produto')
                ->where('parent_id', $marca)
                ->orderBy('nome')
                ->get();

            return response()->json([
                'success' => true,
                'produtos' => $produtos->map(function ($produto) {
                    return [
                        'id' => $produto->id,
                        'slug' => $produto->slug,
                        'nome' => $produto->nome
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar produtos:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao carregar produtos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLinhas($produto)
    {
        try {
            Log::info('Buscando linhas para produto:', ['produto_id' => $produto]);

            $linhas = Categoria::where('tipo', 'produto')
                ->where('nivel', 'linha')
                ->where('parent_id', $produto)
                ->whereHas('produtos', function ($query) {
                    $query->where('is_active', 1)
                        ->whereHas('skus', function ($q) {
                            $q->where('is_active', 1);
                        });
                })
                ->orderBy('nome')
                ->get();

            return response()->json([
                'success' => true,
                'linhas' => $linhas->map(function ($linha) {
                    return [
                        'id' => $linha->id,
                        'slug' => $linha->slug,
                        'nome' => $linha->nome
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar linhas:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao carregar linhas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePdf(Request $request)
    {
        try {
            Log::info('Iniciando geração de PDF do catálogo', [
                'filtros' => $request->all()
            ]);

            $skusQuery = Sku::with([
                'produto',
                'produto.categoria',
                'produto.categoria.parent'
            ])
                ->where('skus.is_active', 1)
                ->whereHas('produto', function ($query) {
                    $query->where('produtos.is_active', 1);
                });

            // Aplicar filtros usando a hierarquia de categorias
            if ($marca = $request->input('marca')) {
                $skusQuery->whereHas('produto.categoria', function ($query) use ($marca) {
                    $query->where(function ($q) use ($marca) {
                        $q->where('nivel', 'marca')
                            ->where('slug', $marca)
                            ->orWhereHas('children', function ($child) use ($marca) {
                                $child->where('parent_id', function ($subquery) use ($marca) {
                                    $subquery->select('id')
                                        ->from('categorias')
                                        ->where('slug', $marca)
                                        ->where('nivel', 'marca');
                                });
                            });
                    });
                });
            }

            if ($produto = $request->input('produto')) {
                $skusQuery->whereHas('produto.categoria', function ($query) use ($produto) {
                    $query->where(function ($q) use ($produto) {
                        $q->where('nivel', 'produto')
                            ->where('slug', $produto)
                            ->orWhereHas('children', function ($child) use ($produto) {
                                $child->where('parent_id', function ($subquery) use ($produto) {
                                    $subquery->select('id')
                                        ->from('categorias')
                                        ->where('slug', $produto)
                                        ->where('nivel', 'produto');
                                });
                            });
                    });
                });
            }

            if ($linha = $request->input('linha')) {
                $skusQuery->whereHas('produto.categoria', function ($query) use ($linha) {
                    $query->where('nivel', 'linha')
                        ->where('slug', $linha);
                });
            }

            // Log da query para debug
            Log::info('Query do PDF:', [
                'sql' => $skusQuery->toSql(),
                'bindings' => $skusQuery->getBindings()
            ]);

            $skus = $skusQuery->get();

            Log::info('Produtos carregados para PDF', [
                'total_produtos' => $skus->count()
            ]);

            $pdf = PDF::loadView('pdfs.catalogo', [
                'skus' => $skus,
                'filtros' => [
                    'marca' => $request->marca,
                    'produto' => $request->produto,
                    'linha' => $request->linha
                ]
            ]);

            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'dpi' => 150,
                'debugCss' => true
            ]);

            return $pdf->download('catalogo-asteca.pdf');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF do catálogo', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Erro ao gerar PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
