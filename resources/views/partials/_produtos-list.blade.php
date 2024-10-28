@foreach($produtos as $produto)
<div class="border p-4 rounded-lg">
    <h2 class="text-xl font-semibold text-gray-800">{{ $produto->nome }}</h2>
    <p class="text-gray-600">{{ $produto->descricao }}</p>

    <!-- Listar os SKUs do produto -->
    @if($produto->skus->count())
    <ul>
        @foreach($produto->skus as $sku)
        <li>
            {{ $sku->nome }} - {{ $sku->quantidade }} ({{ $sku->unidade }})
            <p>Descrição: {{ $sku->descricao }}</p>

            <!-- Verifica se há subcategoria -->
            @if(isset($subcategoriaSlug) && $subcategoriaSlug)
            <a href="#" class="text-red-600 font-semibold hover:underline">
                Ver mais detalhes
            </a>
            @else
            <a href="#" class="text-red-600 font-semibold hover:underline">
                Ver mais detalhes
            </a>
            @endif
        </li>
        @endforeach
    </ul>
    @else
    <p>Sem variações disponíveis.</p>
    @endif
</div>
@endforeach