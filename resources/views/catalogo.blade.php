@extends('layouts.app')

@section('title', 'Catálogo de Produtos')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share currentPage="Catálogo" />

    <!-- Título Principal -->
    <h1 class="text-4xl font-bold mb-6">Catálogo de Produtos</h1>

    <!-- Filtros -->
    <div class="border-b border-gray-300 pb-4 mb-6">
        <!-- Filtro de Marcas -->
        <div class="flex space-x-4 overflow-auto mb-4">
            @foreach ($marcas as $marca)
            <button class="px-4 py-2 rounded-full {{ request('marca') == $marca->id ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800' }}"
                onclick="window.location.href='{{ route('catalogo.index', ['marca' => $marca->id]) }}'">
                {{ strtoupper($marca->nome) }}
            </button>
            @endforeach
        </div>

        <!-- Filtros de Produtos e Linhas -->
        <div class="flex justify-between items-center">
            <!-- Filtro de Produtos -->
            <div class="flex space-x-4">
                <span class="text-lg font-semibold">Escolha o produto:</span>
                @foreach ($produtos as $produto)
                <button class="px-4 py-2 rounded-full {{ request('produto') == $produto->id ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800' }}"
                    onclick="window.location.href='{{ route('catalogo.index', ['marca' => request('marca'), 'produto' => $produto->id]) }}'">
                    {{ strtoupper($produto->nome) }}
                </button>
                @endforeach
            </div>

            <!-- Filtro de Linhas -->
            <div class="flex space-x-4">
                <span class="text-lg font-semibold">Escolha a linha:</span>
                <button class="px-4 py-2 rounded-full {{ request('linha') ? 'bg-gray-200 text-gray-800' : 'bg-black text-white' }}"
                    onclick="window.location.href='{{ route('catalogo.index', ['marca' => request('marca'), 'produto' => request('produto')]) }}'">
                    TODOS
                </button>
                @foreach ($linhas as $linha)
                <button class="px-4 py-2 rounded-full {{ request('linha') == $linha->id ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800' }}"
                    onclick="window.location.href='{{ route('catalogo.index', ['marca' => request('marca'), 'produto' => request('produto'), 'linha' => $linha->id]) }}'">
                    {{ strtoupper($linha->nome) }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Contagem de Produtos e Opções de Ordenação -->
    <div class="flex justify-between items-center mb-4">
        <span class="text-gray-600">{{ $skusPorMarca->flatten()->count() }} Produtos Encontrados</span>
        <button class="text-gray-800">Ordenar</button>
    </div>

    <!-- Grid de Produtos por Marca -->
    @if($skusPorMarca->isEmpty())
    <p class="text-center text-gray-600">Nenhum produto encontrado.</p>
    @else
    <!-- Agrupamento de Produtos por Marca -->
    @foreach ($skusPorMarca as $marcaNome => $skus)
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">{{ $marcaNome }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($skus as $sku)
            <div class="border p-4 rounded-lg text-center">
                <img src="{{ asset('storage/thumbnails/' . $sku->imagem) }}" alt="{{ $sku->produto->nome }}" class="w-full h-48 object-contain mb-4 rounded">
                <h2 class="text-xl font-semibold">{{ $sku->produto->nome }}</h2>
                <p class="text-gray-600">{{ $sku->quantidade }}</p>
                <a href="{{ url("/produto/{$sku->produto->categoria->getMarca()->slug}/{$sku->produto->slug}#{$sku->slug}") }}"
                    class="text-red-600 font-semibold hover:underline">Ver mais detalhes</a>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection