@extends('layouts.app')

@section('title', $receita->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Voltar e Breadcrumb -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('receitas.categoria', $categoria->slug) }}" class="text-red-600 font-semibold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
            Voltar para {{ $categoria->nome }}
        </a>
        <x-breadcrumb-share currentPage="{{ $receita->nome }}" />
    </div>

    <!-- Título da Receita -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Imagem da Receita -->
        <div class="relative">
            <img src="https://via.placeholder.com/800x600?text={{ urlencode($receita->nome) }}" alt="{{ $receita->nome }}" class="w-full rounded-lg shadow-lg object-cover">

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
                <ul class="list-disc list-inside text-gray-700 mt-2">
                    {!! nl2br(e($receita->ingredientes)) !!}
                </ul>
            </div>

            <!-- Modo de Preparo -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Modo de Preparo</h3>
                <ol class="list-decimal list-inside text-gray-700 mt-2 space-y-2">
                    {!! nl2br(e($receita->preparo)) !!}
                </ol>
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