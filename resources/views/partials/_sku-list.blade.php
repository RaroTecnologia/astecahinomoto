@foreach($skus as $sku)
<div class="border p-4 rounded-lg">
    <h3>{{ $sku->nome }} - {{ $sku->quantidade }} ({{ $sku->unidade }})</h3>
    <p>{{ $sku->descricao }}</p>

    <!-- Link para ver mais detalhes do SKU -->
    <a href="#" class="text-red-600 font-semibold hover:underline">
        Ver mais detalhes
    </a>
</div>
@endforeach