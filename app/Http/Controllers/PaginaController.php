<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class PaginaController extends Controller
{
    public function index()
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Carregar as marcas (categorias do tipo marca) com seus tipos
        $marcas = Categoria::where('nivel', 'marca')
            ->with(['tipos' => function($query) {
                $query->wherePivot('is_principal', true);
            }])
            ->orderBy('ordem')
            ->get();

        // Retornar a view principal com as variáveis
        return view('home', compact('tiposHeader', 'marcas'));
    }

    public function sobre()
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Retornar a view sobre com a variável $tipos
        return view('sobre', compact('tiposHeader'));
    }

    public function faleConosco()
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        return view('fale-conosco', compact('tiposHeader'));
    }

    public function politicaPrivacidade()
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        return view('politica-de-privacidade', compact('tiposHeader'));
    }

    public function enviarContato(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'message' => 'required|string',
            'department' => 'required|string',
        ]);

        try {
            // Aqui você pode adicionar a lógica para:
            // 1. Enviar e-mail
            // Mail::to('seu-email@exemplo.com')->send(new ContatoEmail($validated));

            // 2. E/ou salvar no banco de dados
            // Contato::create($validated);

            return back()->with('success', 'Mensagem enviada com sucesso! Em breve entraremos em contato.');
        } catch (\Exception $e) {
            return back()->with('error', 'Desculpe, ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.')
                ->withInput();
        }
    }

    // Adicione mais métodos conforme necessário para outras páginas
}
