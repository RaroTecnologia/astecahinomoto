<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function showMenu()
    {
        // Buscando todos os tipos de produtos para o menu
        $tipos = Tipo::all();

        // Retornando a view com as informações dos tipos
        return view('layouts.app', compact('tipos'));
    }
}
