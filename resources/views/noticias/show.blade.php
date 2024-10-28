@extends('layouts.app')

@section('title', $noticia->titulo)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Voltar e Breadcrumb -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('noticias.index') }}" class="text-red-600 font-semibold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
            Voltar
        </a>
        <x-breadcrumb-share currentPage="{{ $noticia->titulo }}" />
    </div>

    <!-- Título da Notícia e Chamada -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $noticia->titulo }}</h1>

        <!-- Resumo da Notícia -->
        <p class="text-gray-600 leading-relaxed mb-6">
            {{ $noticia->resumo }}
        </p>
    </div>

    <!-- Imagem da Notícia -->
    <div class="mb-8">
        <img src="{{ $noticia->imagem_url }}" alt="{{ $noticia->titulo }}" class="w-full rounded-lg shadow-lg object-cover">
    </div>

    <!-- Corpo da Notícia -->
    <div class="text-gray-700 leading-relaxed text-left mb-8">
        {!! $noticia->conteudo !!}
    </div>

    <!-- Notícias Relacionadas -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Leia Também</h2>
        <x-news-list :noticias="$relacionadas" />
    </div>
</div>
@endsection