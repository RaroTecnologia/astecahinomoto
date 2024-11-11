<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Mail\ContatoEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaginaController extends Controller
{
    public function index()
    {
        // Carregar todos os tipos para o submenu
        $tiposHeader = Tipo::orderBy('ordem')->get();

        // Carregar as marcas (categorias do tipo marca) com seus tipos
        $marcas = Categoria::where('nivel', 'marca')
            ->with(['tipos' => function ($query) {
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
        try {
            Log::info('Iniciando envio de contato', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:255',
                'message' => 'required|string',
                'department' => 'required|string',
            ]);

            Log::info('Dados validados, tentando enviar email');

            Mail::to(config('mail.from.address'))->send(new ContatoEmail($validated));

            Log::info('Email enviado com sucesso');

            return redirect()->back()
                ->with('success', 'Mensagem enviada com sucesso! Em breve entraremos em contato.');
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Desculpe, ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.')
                ->withInput();
        }
    }
}
