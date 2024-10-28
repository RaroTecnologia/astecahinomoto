@extends('layouts.app')

@section('title', 'Linhas e Produtos de ' . $categoriaMarca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share currentPage="{{ $categoriaMarca->nome }}" parentPage="Nossas Marcas" />

    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">Linhas e Produtos da Marca {{ $categoriaMarca->nome }}</h1>
    </div>

    @if($subcategorias->isNotEmpty())
    <!-- Listagem de Linhas/Subcategorias -->
    <div class="flex flex-wrap justify-center gap-8">
        @foreach($subcategorias as $subcategoria)
        <a href="{{ route('nossas-marcas.linhas.produtos', ['slugMarca' => $categoriaMarca->slug, 'slugLinha' => $subcategoria->slug]) }}" class="block border p-6 rounded-lg shadow-lg hover:bg-gray-50 transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
            <div class="flex flex-col h-full">
                <img src="{{ $subcategoria->imagem ? asset('storage/subcategorias/' . $subcategoria->imagem) : asset('assets/sem_imagem.png') }}"
                    alt="{{ $subcategoria->nome }}"
                    class="w-full h-48 object-contain mb-4 rounded">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $subcategoria->nome }}</h2>
                <p class="text-gray-600 flex-grow text-center">{{ $subcategoria->descricao }}</p>
                <span class="text-red-600 font-semibold hover:underline mt-4 text-center">Ver produtos</span>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    @if($produtosDiretos->isNotEmpty())
    <!-- Listagem de Produtos Diretos -->
    <h2 class="text-2xl font-bold mt-8 mb-4">Produtos</h2>
    <div class="flex flex-wrap justify-center gap-8">
        @foreach($produtosDiretos as $produto)
        <a href="{{ route('produtos.show', [$categoriaMarca->slug, $produto->slug]) }}" class="block border p-6 rounded-lg shadow-lg hover:bg-gray-50 transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
            <div class="flex flex-col h-full">
                <img src="{{ $produto->imagem ? asset('storage/produtos/' . $produto->imagem) : asset('assets/sem_imagem.png') }}"
                    alt="{{ $produto->nome }}"
                    class="w-full h-48 object-contain mb-4 rounded">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $produto->nome }}</h2>
                <p class="text-gray-600 flex-grow text-center">{{ $produto->descricao }}</p>
                <span class="text-red-600 font-semibold hover:underline mt-4 text-center">Ver mais detalhes</span>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection