<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TabelaNutricional;
use App\Models\Nutriente;
use Illuminate\Http\Request;

class NutritionTableController extends Controller
{
    public function index()
    {
        // Carregar todas as tabelas nutricionais com seus nutrientes associados
        $tabelasNutricionais = TabelaNutricional::with('nutrientes')->get();

        // Carregar todos os nutrientes disponíveis
        $nutrientes = Nutriente::all();

        return view('web-admin.tabelas-nutricionais.index', compact('tabelasNutricionais', 'nutrientes'));
    }
    public function create()
    {
        $nutrientes = Nutriente::all();
        return view('web-admin.tabelas-nutricionais.create', compact('nutrientes'));
    }

    public function store(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'nome' => 'required|string|max:255',
            'porcao_caseira' => 'nullable|string|max:255',
            'segundo_valor' => 'nullable|string|max:20',
        ]);

        // Criação da tabela nutricional
        $tabelaNutricional = TabelaNutricional::create([
            'nome' => $request->input('nome'),
            'porcao_caseira' => $request->input('porcao_caseira'),
            'segundo_valor' => $request->input('segundo_valor'),
        ]);

        // Verifica se o redirecionamento para edição foi solicitado
        if ($request->has('redirect') && $request->input('redirect') === 'edit') {
            return redirect()->route('web-admin.tabelas-nutricionais.edit', $tabelaNutricional->id)
                ->with('success', 'Tabela criada e pronta para edição.');
        }

        return redirect()->route('web-admin.tabelas-nutricionais.index')->with('success', 'Tabela criada com sucesso.');
    }


    public function edit($id)
    {
        $tabelaNutricional = TabelaNutricional::with('nutrientes')->findOrFail($id);
        $nutrientes = Nutriente::all();
        return view('web-admin.tabelas-nutricionais.edit', compact('tabelaNutricional', 'nutrientes'));
    }

    public function update(Request $request, $id)
    {
        $tabelaNutricional = TabelaNutricional::findOrFail($id);
        $tabelaNutricional->update([
            'nome' => $request->input('nome'),
            'porcao_caseira' => $request->input('porcao_caseira'),
            'segundo_valor' => $request->input('segundo_valor'),
        ]);

        // Limpar nutrientes existentes antes de adicionar os novos
        $tabelaNutricional->nutrientes()->detach();

        foreach ($request->input('nutrientes') as $nutrienteId => $valores) {
            $tabelaNutricional->nutrientes()->attach($nutrienteId, [
                'valor_por_100g' => $valores['valor_por_100g'] ?? null,
                'valor_por_porção' => $valores['valor_por_porção'] ?? null,
                'valor_diario' => $valores['valor_diario'] ?? null,
            ]);
        }

        return redirect()->route('web-admin.tabelas-nutricionais.index')
            ->with('success', 'Tabela nutricional atualizada com sucesso.');
    }


    public function destroy($id)
    {
        $tabelaNutricional = TabelaNutricional::findOrFail($id);
        $tabelaNutricional->delete();
        return redirect()->route('web-admin.tabelas-nutricionais.index')->with('success', 'Tabela nutricional excluída com sucesso.');
    }
}
