<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebAdminController extends Controller
{
    /**
     * Exibe o dashboard administrativo.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Aqui você pode passar dados para o dashboard, como contagem de usuários, produtos, etc.
        return view('web-admin.dashboard');
    }
}
