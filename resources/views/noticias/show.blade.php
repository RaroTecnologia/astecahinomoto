@extends('layouts.app')

@section('title', $noticia->titulo)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <x-breadcrumb-share
            currentPage=""
            parentRoute="noticias.index"
            parentText="Notícias" />
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