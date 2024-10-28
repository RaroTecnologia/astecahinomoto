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

        // Retornar a view principal com a variável $tipos
        return view('home', compact('tiposHeader'));
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
    // Adicione mais métodos conforme necessário para outras páginas
}
