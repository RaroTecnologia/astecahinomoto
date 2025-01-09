<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Categoria;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ReceitaController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Pegar todas as categorias do tipo receita
            $categorias = Categoria::where('tipo', 'receita')
                ->where('nome', '!=', 'Receitas')
                ->orderBy('nome')
                ->get();

            // Query base
            $query = Receita::with(['categoria']) // Eager loading para evitar N+1
                ->where('status', 'publicado');

            // Filtrar por categoria se especificado
            if ($request->filled('categoria')) {
                $query->where('categoria_id', $request->categoria);
            }

            // Ordenação
            $order = $request->get('order', 'recent');
            switch ($order) {
                case 'recent':
                    $query->latest();
                    break;
                case 'likes':
                    $query->orderBy('curtidas', 'desc');
                    break;
                default:
                    $query->latest();
            }

            // Executar a query com paginação
            $receitas = $query->paginate(12)->withQueryString(); // Adiciona withQueryString() para manter os filtros

            // Carregar tipos para o submenu
            $tiposHeader = Tipo::orderBy('ordem')->get();

            // Se for uma requisição AJAX, retornar JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('receitas._list', compact('receitas'))->render(),
                    'pagination' => view('vendor.pagination.custom', ['paginator' => $receitas])->render()
                ]);
            }

            return view('receitas.index', compact('receitas', 'categorias', 'tiposHeader'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar receitas:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }


    public function show($categoriaSlug, $slug)
    {
        // Carregar tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Buscar a categoria
        $categoria = Categoria::where('slug', $categoriaSlug)
            ->where('tipo', 'receita')
            ->firstOrFail();

        // Buscar a receita pelo slug e categoria
        $receita = Receita::where('slug', $slug)
            ->where('categoria_id', $categoria->id)
            ->where('status', 'publicado')
            ->firstOrFail();

        // Buscar sugestões de receitas
        $sugestoes = Receita::where('categoria_id', $categoria->id)
            ->where('id', '!=', $receita->id)
            ->where('status', 'publicado')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('receitas.show', compact('receita', 'categoria', 'sugestoes', 'tiposHeader'));
    }


    public function categoriasReceitas($slug)
    {
        // Buscar a categoria pelo slug
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        // Buscar todas as receitas dessa categoria
        $receitas = Receita::where('categoria_id', $categoria->id)->paginate(10);

        // Retornar a view com as receitas da categoria
        return view('categorias.receitas', compact('categoria', 'receitas'));
    }

    public function filtrarPorCategoria($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();
        $receitas = Receita::with('categoria')
            ->where('categoria_id', $categoria->id)
            ->where('status', 'publicada')
            ->paginate(8);

        return response()->json([
            'receitas' => view('partials._receitas-list', compact('receitas'))->render(),
            'pagination' => view('partials._pagination', compact('receitas'))->render(),
            'count' => $receitas->total()
        ]);
    }

    public function curtir($id)
    {
        try {
            Log::info('Iniciando curtida', ['receita_id' => $id]);

            $receita = Receita::findOrFail($id);
            $cookieName = 'receita_curtida_' . $id;

            // Log para verificar cookies
            Log::info('Verificando cookies', [
                'todos_cookies' => request()->cookies->all(),
                'cookie_especifico' => request()->cookie($cookieName)
            ]);

            if (request()->cookie($cookieName)) {
                Log::info('Tentativa de curtir novamente', ['receita_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Você já curtiu esta receita',
                    'curtidas' => $receita->curtidas
                ], 403);
            }

            // Incrementa curtidas
            $receita->increment('curtidas');

            Log::info('Curtida realizada', [
                'receita_id' => $id,
                'curtidas_anterior' => $receita->curtidas - 1,
                'curtidas_atual' => $receita->curtidas
            ]);

            // Cria o cookie
            $cookie = cookie($cookieName, true, 60 * 24 * 30); // 30 dias

            return response()->json([
                'success' => true,
                'message' => 'Receita curtida com sucesso',
                'curtidas' => $receita->curtidas
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            Log::error('Erro ao curtir receita', [
                'receita_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao curtir receita'
            ], 500);
        }
    }
}
