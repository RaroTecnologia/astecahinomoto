@forelse($receitas as $receita)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <x-card-item
            title="{{ $receita->nome }}"
            description="{{ Str::limit($receita->ingredientes, 100) }}"
            image="https://via.placeholder.com/600x400?text={{ urlencode($receita->nome) }}"
            link="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
            linkText="Ver Receita" />
        <div class="text-sm text-gray-600">
            {{ $receita->categoria->nome }}
        </div>
    </div>
@empty
    <div class="col-span-full flex flex-col items-center justify-center py-12">
        <i class="fas fa-utensils text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600">Nenhuma receita disponível</h3>
        <p class="text-gray-500 mt-2">No momento não há receitas publicadas nesta categoria.</p>
    </div>
@endforelse