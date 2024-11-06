<div class="mb-8 relative">
    <div class="relative">
        <input
            type="text"
            id="search-{{ $context }}"
            data-search="{{ $context }}"
            placeholder="Buscar {{ $placeholder }}..."
            class="px-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-vermelho-asteca/50 focus:border-vermelho-asteca pr-10"
            autocomplete="off">
        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div id="autocomplete-results-{{ $context }}"
        class="absolute bg-white shadow-lg mt-1 w-full max-h-48 overflow-y-auto hidden rounded-lg border border-gray-200 z-50">
        <!-- Loading state -->
        <div id="autocomplete-loading-{{ $context }}" class="hidden p-4 text-center text-gray-500">
            <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-vermelho-asteca"></div>
            <span class="ml-2">Buscando...</span>
        </div>
        <!-- No results state -->
        <div id="autocomplete-no-results-{{ $context }}" class="hidden p-4 text-center text-gray-500">
            Nenhum resultado encontrado
        </div>
        <!-- Results will be inserted here -->
    </div>
</div>