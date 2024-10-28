<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nutriente;
use Illuminate\Http\Request;

class NutrientController extends Controller
{
    public function index()
    {
        // Carregar todos os nutrientes
        $nutrientes = Nutriente::all();
        return view('web-admin.nutrientes.index', compact('nutrientes'));
    }

    public function create()
    {
        return view('web-admin.nutrientes.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'nome' => 'required|string|max:255',
            'unidade_medida' => 'required|string|max:10',
            'tipo_nutriente' => 'required|string|max:255',
        ]);

        // Criar um novo nutriente
        Nutriente::create($request->all());
        return redirect()->route('web-admin.nutrientes.index')->with('success', 'Nutriente criado com sucesso.');
    }

    public function edit($id)
    {
        // Buscar o nutriente para edição
        $nutriente = Nutriente::findOrFail($id);
        return view('web-admin.nutrientes.edit', compact('nutriente'));
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados de entrada
        $request->validate([
            'nome' => 'required|string|max:255',
            'unidade_medida' => 'required|string|max:10',
            'tipo_nutriente' => 'required|string|max:255',
        ]);

        // Atualizar o nutriente existente
        $nutriente = Nutriente::findOrFail($id);
        $nutriente->update($request->all());

        return redirect()->route('web-admin.nutrientes.index')->with('success', 'Nutriente atualizado com sucesso.');
    }

    public function destroy($id)
    {
        // Excluir o nutriente
        $nutriente = Nutriente::findOrFail($id);
        $nutriente->delete();

        return redirect()->route('web-admin.nutrientes.index')->with('success', 'Nutriente excluído com sucesso.');
    }
}
