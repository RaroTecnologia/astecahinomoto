<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

abstract class Controller
{
    //
}

Route::get('/sobre-asteca-hinomoto', function () {
    return view('sobre');
})->name('sobre-asteca-hinomoto');

Route::get('/fale-conosco', function () {
    return view('fale-conosco');
})->name('fale-conosco');
