@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container mx-auto py-16 px-4">
    <h1 class="text-3xl font-bold mb-6">Bem-vindo ao Painel Administrativo</h1>

    <p>Use o menu ao lado para gerenciar o conteúdo do site.</p>

    <div class="grid grid-cols-3 gap-4 mt-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold">Usuários</h2>
            <p>Gerenciar os usuários do sistema.</p>
            <a href="{{ route('web-admin.usuarios.index') }}" class="text-blue-600">Ver mais</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold">Notícias</h2>
            <p>Gerenciar as notícias publicadas no site.</p>
            <a href="{{ route('web-admin.noticias.index') }}" class="text-blue-600">Ver mais</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold">Receitas</h2>
            <p>Gerenciar as receitas publicadas no site.</p>
            <a href="{{ route('web-admin.receitas.index') }}" class="text-blue-600">Ver mais</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold">Categorias</h2>
            <p>Gerenciar categorias de notícias e receitas.</p>
            <a href="{{ route('web-admin.categorias.index') }}" class="text-blue-600">Ver mais</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold">Produtos</h2>
            <p>Gerenciar os produtos e suas categorias.</p>
            <a href="#" class="text-blue-600">Ver mais</a>
        </div>
    </div>
</div>
@endsection