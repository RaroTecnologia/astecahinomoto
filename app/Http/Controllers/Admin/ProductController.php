<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\TabelaNutricional;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('categoria');

        // Filtro de categoria
        if ($request->filled('categoria')) {
            // Buscar a categoria selecionada
            $categoria = Categoria::with('subcategorias')->find($request->categoria);

            // Obter todos os IDs da categoria e suas subcategorias
            if ($categoria) {
                $categoriaIds = $this->getCategoriaIdsRecursively($categoria);
                $query->whereIn('categoria_id', $categoriaIds);
            }
        }

        // Busca por nome do produto
        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        // Paginação
        $produtos = $query->paginate(25);

        // Obter todas as categorias para o dropdown de filtros
        $categorias = Categoria::with('subcategorias')
            ->whereNull('parent_id')
            ->where('tipo', 'produto')
            ->get();

        return view('web-admin.produtos.index', compact('produtos', 'categorias'));
    }

    public function create()
    {
        $tabelasNutricionais = TabelaNutricional::all();
        $categorias = Categoria::where('tipo', 'produto')->get();
        return view('web-admin.produtos.create', compact('tabelasNutricionais', 'categorias'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'categoria_id' => 'nullable|exists:categorias,id',
            ]);

            $nomeProduto = $request->input('nome');
            $slug = Str::slug($nomeProduto);

            // Cria o produto
            $produto = Produto::create([
                'nome' => $nomeProduto,
                'slug' => $slug,
                'categoria_id' => $request->input('categoria_id'),
            ]);

            // Verificar se o redirecionamento para edição foi solicitado
            if ($request->has('redirect') && $request->redirect === 'edit') {
                return redirect()->route('web-admin.produtos.edit', $produto->id)->with('success', 'Produto criado e pronto para edição.');
            }

            return redirect()->route('web-admin.produtos.index')->with('success', 'Produto criado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar produto: ' . $e->getMessage());
            return redirect()->back()->withErrors('Ocorreu um erro ao criar o produto.');
        }
    }

    public function edit($id)
    {
        try {
            $produto = Produto::with('allSkus')
                ->findOrFail($id);
            $tabelasNutricionais = TabelaNutricional::all();
            $categorias = Categoria::where('tipo', 'produto')->get();
            return view('web-admin.produtos.edit', compact('produto', 'tabelasNutricionais', 'categorias'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar produto para edição: ' . $e->getMessage());
            return redirect()->route('web-admin.produtos.index')->withErrors('Produto não encontrado.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'ingredientes' => 'nullable|string',
                'tabela_nutricional_id' => 'nullable|exists:tabelas_nutricionais,id',
                'categoria_id' => 'nullable|exists:categorias,id',
                'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'is_active' => 'nullable',
            ]);

            $produto = Produto::findOrFail($id);

            // Processa a imagem se houver upload
            if ($request->hasFile('imagem')) {
                if ($produto->imagem && Storage::exists('public/produtos/' . $produto->imagem)) {
                    Storage::delete('public/produtos/' . $produto->imagem);
                }
                $imagePath = $request->file('imagem')->store('produtos', 'public');
                $produto->imagem = basename($imagePath);
            }

            // Converte o status para 0 ou 1
            $isActive = $request->has('is_active') ? 1 : 0;

            // Atualiza os dados do produto
            $produto->update([
                'nome' => $request->input('nome'),
                'slug' => $produto->nome !== $request->input('nome') ? Str::slug($request->input('nome')) : $produto->slug,
                'descricao' => $request->input('descricao'),
                'ingredientes' => $request->input('ingredientes'),
                'categoria_id' => $request->input('categoria_id'),
                'tabela_nutricional_id' => $request->input('tabela_nutricional_id'),
                'is_active' => $isActive, // Valor 0 ou 1
            ]);

            return response()->json(['success' => true, 'message' => 'Produto atualizado com sucesso.']);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar produto.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            $produto->delete();

            return redirect()->route('web-admin.produtos.index')->with('success', 'Produto excluído com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir produto: ' . $e->getMessage());
            return redirect()->route('web-admin.produtos.index')->withErrors('Ocorreu um erro ao excluir o produto.');
        }
    }

    function getCategoriaIdsRecursively($categoria)
    {
        $ids = [$categoria->id]; // Começa com o id da categoria atual

        // Se a categoria tiver subcategorias, percorra recursivamente
        foreach ($categoria->subcategorias as $subcategoria) {
            $ids = array_merge($ids, $this->getCategoriaIdsRecursively($subcategoria));
        }

        return $ids;
    }
}
