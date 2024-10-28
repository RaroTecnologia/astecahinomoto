@extends('layouts.app')

@section('title', 'Notícias')

@section('content')
<div class="relative bg-white pb-24">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb e Opção de Compartilhar -->
        <x-breadcrumb-share currentPage="Notícias" />

        <!-- Título da Página -->
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-900">Notícias</h1>
        </div>

        <!-- Filtros e Ordenação -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="text-gray-600 text-sm"><span id="count">{{ $noticias->count() }}</span> Artigos Encontrados</span>
                <span class="mx-2 text-gray-400">|</span>
                <a href="#" id="clear-filters" class="text-red-600 font-semibold text-sm">Limpar Filtros X</a>
            </div>
            <div class="flex items-center">
                <button class="flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M3 12h18m-7 6h7"></path>
                    </svg>
                    Ordenar
                </button>
            </div>
        </div>

        <!-- Filtros por categoria -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Filtrar por:</h3>
            <div class="flex space-x-2" id="categories">
                @foreach($categorias as $categoria)
                <a href="{{ route('noticias.categoria', ['slug' => $categoria->slug]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-red-600 hover:text-white transition">
                    {{ $categoria->nome }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Campo de busca com autocomplete -->
        <x-autocomplete context="noticias" placeholder="Notícias" />

        <!-- Listagem de Notícias -->
        <div id="news-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($noticias as $noticia)
            <x-card-item
                title="{{ $noticia->titulo }}"
                description="{{ Str::limit($noticia->resumo, 100) }}"
                image="{{ $noticia->imagem_url }}"
                link="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}"
                linkText="Ler Mais" />
            @endforeach
        </div>

        <!-- Paginação -->
        <div id="pagination" class="mt-8">
            {{ $noticias->links() }}
        </div>
    </div>
</div>
@endsection