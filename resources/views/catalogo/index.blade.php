<x-app-layout>
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
        <form id="filtro-form" class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Categoria -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                <select name="categoria" id="categoria" class="w-full border rounded-lg p-2">
                    <option value="">Todas as Categorias</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @foreach($categoria->children as $subcategoria)
                    <option value="{{ $subcategoria->id }}">&nbsp;&nbsp;- {{ $subcategoria->nome }}</option>
                    @endforeach
                    @endforeach
                </select>
            </div>

            <!-- Marca -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                <select name="marca" id="marca" class="w-full border rounded-lg p-2">
                    <option value="">Todas as Marcas</option>
                    @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}">{{ $marca->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Produto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produto</label>
                <select name="produto" id="produto" class="w-full border rounded-lg p-2">
                    <option value="">Todos os Produtos</option>
                </select>
            </div>

            <!-- Linha -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Linha</label>
                <select name="linha" id="linha" class="w-full border rounded-lg p-2">
                    <option value="">Todas as Linhas</option>
                </select>
            </div>
        </form>

        <!-- Grid de Produtos -->
        <div id="produtos-container">
            @include('partials.catalogo.produtos-grid', ['skus' => $skus ?? collect()])
        </div>

        <!-- Loading State -->
        <div id="loading-more" class="hidden">
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/catalogo.js') }}"></script>
    @endpush
</x-app-layout>