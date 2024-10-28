<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    public function index()
    {
        // Carregar receitas com a respectiva categoria
        $receitas = Receita::with('categoria')->paginate(10);

        // Retornar a view com os dados
        return view('web-admin.receitas.index', compact('receitas'));
    }

    public function create()
    {
        // Buscar todas as subcategorias cujas categorias pai são do tipo 'receita'
        $categorias = Categoria::whereHas('categoriaPai', function ($query) {
            $query->where('tipo', 'receitas');
        })->get();

        return view('web-admin.receitas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            // Outras validações necessárias...
        ]);

        // Gerar o slug automaticamente a partir do nome
        $slug = Str::slug($request->input('nome'));

        // Criar a receita com os dados e o slug gerado
        Receita::create([
            'nome' => $request->input('nome'),
            'slug' => $slug, // Gerado automaticamente
            'categoria_id' => $request->input('categoria_id'),
            'ingredientes' => $request->input('ingredientes'),
            'modo_preparo' => $request->input('modo_preparo'),
            'compartilhamentos' => $request->input('compartilhamentos') ?? 0,
            'curtidas' => $request->input('curtidas') ?? 0,
            'dificuldade' => $request->input('dificuldade'),
            'tempo_preparo' => $request->input('tempo_preparo'),
        ]);

        return redirect()->route('web-admin.receitas.index')->with('success', 'Receita criada com sucesso');
    }

    // Método edit para carregar a receita e exibir o formulário de edição
    public function edit($id)
    {
        $receita = Receita::findOrFail($id);

        // Buscar todas as categorias que pertencem ao tipo 'receita'
        $categorias = Categoria::whereHas('categoriaPai', function ($query) {
            $query->where('tipo', 'receitas');
        })->get();

        return view('web-admin.receitas.edit', compact('receita', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados recebidos
        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        // Buscar a receita para atualizar
        $receita = Receita::findOrFail($id);

        // Atualizar os dados da receita
        $receita->update([
            'nome' => $request->input('nome'),
            'slug' => Str::slug($request->input('nome')), // Gerar o slug automaticamente
            'categoria_id' => $request->input('categoria_id'),
            'ingredientes' => $request->input('ingredientes'),
            'modo_preparo' => $request->input('modo_preparo'),
            'compartilhamentos' => $request->input('compartilhamentos') ?? $receita->compartilhamentos,
            'curtidas' => $request->input('curtidas') ?? $receita->curtidas,
            'dificuldade' => $request->input('dificuldade'),
            'tempo_preparo' => $request->input('tempo_preparo'),
        ]);

        return redirect()->route('web-admin.receitas.index')->with('success', 'Receita atualizada com sucesso');
    }
}
