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
    <div class="relative w-full">
        <div class="animate-pulse bg-gray-200 w-full h-[400px] md:h-[500px] lg:h-[600px] rounded-lg"></div>
        <img
            src="{{ $noticia->imagem ? asset('storage/noticias/' . $noticia->imagem) : asset('assets/sem_imagem.png') }}"
            alt="{{ $noticia->titulo }}"
            class="w-full h-[400px] md:h-[500px] lg:h-[600px] rounded-lg shadow-lg object-cover"
            onerror="this.src='{{ asset('assets/sem_imagem.png') }}'"
            onload="this.previousElementSibling.remove()">
    </div>

    <!-- Corpo da Notícia -->
    <div class="text-gray-700 leading-relaxed text-left mb-8 mt-8">
        {!! $noticia->conteudo !!}
    </div>

    <!-- Notícias Relacionadas -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Leia Também</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relacionadas as $noticia)
            <x-card-item
                title="{{ $noticia->titulo }}"
                description="{{ Str::limit($noticia->resumo, 100) }}"
                image="{{ $noticia->imagem_url }}"
                link="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}"
                linkText="Ler Mais" />
            @endforeach
        </div>
    </div>
</div>
@endsection