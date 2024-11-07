@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        currentPage="Catálogo de Produtos"
        parentText="Produtos"
        parentRoute="catalogo.index" />

    <!-- Título e Descrição (Centralizado) -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Catálogo de Produtos</h1>
        <p class="text-gray-600">Explore nossa linha completa de produtos</p>
    </div>

    <!-- Autocomplete Search -->
    <x-autocomplete context="produtos" placeholder="produtos" />

    <!-- Filtros -->
    <div class="flex justify-center mb-16">
        <!-- Filtros Dropdown -->
        <div class="flex flex-wrap justify-center gap-4">
            <!-- Marca -->
            <div class="flex items-center gap-2">
                <span class="px-4 py-1.5 border border-gray-700 rounded-full text-base font-medium">
                    Marca
                </span>
                <div class="relative">
                    <button id="marcaFilter" class="flex items-center justify-between w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50">
                        <span id="selectedMarca">Todas as Marcas</span>
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div id="marcaDropdown" class="hidden absolute left-0 z-10 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg">
                        <a href="#" data-marca="" data-marca-slug="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todas as Marcas</a>
                        @foreach($marcas as $marca)
                        <a href="#"
                            data-marca="{{ $marca->id }}"
                            data-marca-slug="{{ $marca->slug }}"
                            class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                            {{ $marca->nome }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Produto -->
            <div class="flex items-center gap-2">
                <span class="px-4 py-1.5 border border-gray-700 rounded-full text-base font-medium">
                    Produto
                </span>
                <div class="relative">
                    <button id="produtoFilter" class="flex items-center justify-between w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 opacity-50 cursor-not-allowed" disabled>
                        <span id="selectedProduto">Todos os Produtos</span>
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div id="produtoDropdown" class="hidden absolute left-0 z-10 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg">
                        <a href="#" data-produto="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todos os Produtos</a>
                    </div>
                </div>
            </div>

            <!-- Linha -->
            <div class="flex items-center gap-2">
                <span class="px-4 py-1.5 border border-gray-700 rounded-full text-base font-medium">
                    Linha
                </span>
                <div class="relative">
                    <button id="linhaFilter" class="flex items-center justify-between w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 opacity-50 cursor-not-allowed" disabled>
                        <span id="selectedLinha">Todas as Linhas</span>
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div id="linhaDropdown" class="hidden absolute left-0 z-10 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg">
                        <a href="#" data-linha="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todas as Linhas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Produtos -->
    <div id="produtos-container">
        @include('partials.catalogo.produtos-grid', ['skus' => $skus ?? collect()])
    </div>

    <!-- Paginação -->
    <div id="paginacao-container" class="mt-8">
        {{ $skus->links('vendor.pagination.custom') }}
    </div>

    <!-- Loading State -->
    <div id="loading-more" class="hidden">
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
        </div>
    </div>
</div>
@endsection

@vite(['resources/js/autocomplete.js', 'resources/js/catalogo.js'])