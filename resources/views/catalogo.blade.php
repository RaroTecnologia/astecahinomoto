@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Título e Descrição -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Catálogo de Produtos</h1>
        <p class="text-gray-600">Explore nossa linha completa de produtos</p>
    </div>

    <!-- Barra de Busca -->
    <div class="mb-8 relative">
        <input type="text"
            class="w-full p-4 border rounded-lg shadow-sm"
            placeholder="Buscar produtos..."
            data-search="produtos"
            autocomplete="off">

        <!-- Container de Resultados do Autocomplete -->
        <div id="autocomplete-results-produtos"
            class="absolute z-50 w-full bg-white border rounded-lg shadow-lg mt-1 hidden">
            <!-- Loading State -->
            <div id="autocomplete-loading-produtos" class="p-4 hidden">
                <div class="animate-pulse flex space-x-4">
                    <div class="flex-1 space-y-4 py-1">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="space-y-2">
                            <div class="h-4 bg-gray-200 rounded"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <!-- Filtros Dropdown -->
        <div class="flex flex-wrap gap-4">
            <!-- Marca -->
            <div class="relative">
                <button id="marcaFilter" class="flex items-center justify-between w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50">
                    <span id="selectedMarca">Todas as Marcas</span>
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div id="marcaDropdown" class="hidden absolute left-0 z-10 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg">
                    <a href="#" data-marca="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todas as Marcas</a>
                    @foreach($marcas as $marca)
                    <a href="#" data-marca="{{ $marca->id }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                        {{ $marca->nome }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Produto -->
            <div class="relative">
                <button id="produtoFilter"
                    class="flex items-center justify-between w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 opacity-50 cursor-not-allowed"
                    disabled>
                    <span id="selectedProduto">Todos os Produtos</span>
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div id="produtoDropdown" class="hidden absolute left-0 z-10 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg">
                    <a href="#" data-produto="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todos os Produtos</a>
                </div>
            </div>

            <!-- Linha -->
            <div class="relative">
                <button id="linhaFilter"
                    class="flex items-center justify-between w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 opacity-50 cursor-not-allowed"
                    disabled>
                    <span id="selectedLinha">Todas as Linhas</span>
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div id="linhaDropdown" class="hidden absolute left-0 z-10 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg">
                    <a href="#" data-linha="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todas as Linhas</a>
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

@vite(['resources/js/catalogo.js'])