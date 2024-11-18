<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contato;
use App\Models\User;
use App\Models\Tarefa;
use App\Models\AnotacaoContato;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CrmController extends Controller
{
    public function index()
    {
        try {
            Log::info('Iniciando carregamento da página CRM');

            $contatos = Contato::with(['anotacoes.user', 'usuarioAtribuido'])
                ->orderBy('created_at', 'desc')
                ->get();

            $usuarios = User::all();

            Log::info('Dados carregados com sucesso', [
                'total_contatos' => $contatos->count(),
                'total_usuarios' => $usuarios->count()
            ]);

            return view('web-admin.crm.index', compact('contatos', 'usuarios'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar página CRM', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile()
            ]);

            throw $e;
        }
    }

    public function getDetails(Contato $contato)
    {
        try {
            Log::info('Iniciando getDetails', ['contato_id' => $contato->id]);

            $response = [
                'id' => $contato->id,
                'nome' => $contato->nome,
                'email' => $contato->email,
                'telefone' => $contato->telefone,
                'cidade' => $contato->cidade,
                'departamento' => $contato->departamento,
                'status' => $contato->status,
                'mensagem' => $contato->mensagem,
                'created_at' => $contato->created_at->format('d/m/Y H:i'),
                'ultima_interacao' => $contato->ultima_interacao ?
                    $contato->ultima_interacao->format('d/m/Y H:i') :
                    'Sem interações',
                'anotacoes' => $contato->anotacoes->map(function ($anotacao) {
                    return [
                        'id' => $anotacao->id,
                        'conteudo' => $anotacao->conteudo,
                        'created_at' => $anotacao->created_at->format('d/m/Y H:i'),
                        'user' => [
                            'name' => $anotacao->user->name
                        ]
                    ];
                })
            ];

            Log::info('Dados preparados com sucesso', ['response' => $response]);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Erro em getDetails', [
                'contato_id' => $contato->id,
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Erro ao buscar detalhes do contato: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeAnotacao(Request $request, Contato $contato)
    {
        $request->validate([
            'conteudo' => 'required|string'
        ]);

        $anotacao = new AnotacaoContato([
            'conteudo' => $request->conteudo,
            'user_id' => Auth::id()
        ]);

        $contato->anotacoes()->save($anotacao);
        $contato->update(['ultima_interacao' => now()]);

        return response()->json([
            'success' => true,
            'anotacao' => [
                'id' => $anotacao->id
            ]
        ]);
    }

    public function update(Request $request, Contato $contato)
    {
        $contato->update($request->all());
        return redirect()->back()->with('success', 'Contato atualizado com sucesso!');
    }

    public function updateStatus(Request $request, Contato $contato)
    {
        try {
            // Valida o status
            if (!in_array($request->status, Contato::STATUS_VALIDOS)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status inválido'
                ], 422);
            }

            $contato->update([
                'status' => $request->status,
                'ultima_interacao' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status do contato:', [
                'contato_id' => $contato->id,
                'novo_status' => $request->status,
                'erro' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status'
            ], 500);
        }
    }

    public function storeTarefa(Request $request, Contato $contato)
    {
        $tarefa = new Tarefa($request->all());
        $tarefa->contato_id = $contato->id;
        $tarefa->criado_por = Auth::id();
        $tarefa->status = 'pendente';
        $tarefa->save();

        return redirect()->back()->with('success', 'Tarefa criada com sucesso!');
    }

    public function updateTarefa(Request $request, Tarefa $tarefa)
    {
        $tarefa->update($request->all());
        return redirect()->back()->with('success', 'Tarefa atualizada com sucesso!');
    }
}
