@extends('layouts.app')

@section('title', $receita->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <x-breadcrumb-share
            currentPage="{{ $receita->nome }}"
            parentRoute="receitas.index"
            parentText="Receitas" />
    </div>

    <!-- Título da Receita -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Imagem da Receita -->
        <div class="relative">
            <img src="{{ $receita->imagem ? asset('storage/receitas/' . $receita->imagem) : 'assets/sem_imagem.png' }}" alt="{{ $receita->nome }}" class="w-full rounded-lg shadow-lg object-cover">

            <!-- Botões de Compartilhar e Curtir -->
            <div class="flex space-x-4 mt-4">
                <a href="#" class="flex items-center text-gray-600 hover:text-red-600 transition">
                    <i class="fa-regular fa-share-from-square mr-1"></i>
                    Compartilhar esta receita
                </a>
                <a href="#" class="flex items-center text-gray-600 hover:text-red-600 transition">
                    <i class="far fa-heart mr-1"></i>
                    Curtir
                </a>
            </div>

            <!-- Tags -->
            <div class="flex space-x-2 mt-4">
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm">{{ $categoria->nome }}</button>
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm">Oriental</button>
            </div>
        </div>

        <!-- Conteúdo da Receita -->
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $receita->nome }}</h1>

            <!-- Informações de Dificuldade e Tempo de Preparo -->
            <div class="flex items-center mb-6">
                <span class="text-sm text-gray-600">Dificuldade:</span>
                <span class="ml-2 text-red-600 font-semibold">{{ $receita->dificuldade }}</span>
                <span class="mx-2 text-gray-400">•</span>
                <span class="text-sm text-gray-600">Tempo de Preparo:</span>
                <span class="ml-2 text-red-600 font-semibold flex items-center">
                    <i class="fas fa-clock mr-1"></i> {{ $receita->tempo_preparo }} min
                </span>
            </div>

            <!-- Ingredientes -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Ingredientes</h3>
                <div class="ql-editor">{!! $receita->ingredientes !!}</div>
            </div>

            <!-- Modo de Preparo -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Modo de Preparo</h3>
                <div class="ql-editor">{!! $receita->modo_preparo !!}</div>
            </div>
        </div>
    </div>

    <!-- Sugestões de Receitas -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Experimente Também</h2>
        <x-receitas-list :receitas="$sugestoes" />
    </div>
</div>
@endsection