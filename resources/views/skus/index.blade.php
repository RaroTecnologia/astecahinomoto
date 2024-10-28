@extends('layouts.app')

@section('title', 'Lista de SKUs para ' . $produto->nome)

@section('content')
<div class="container">
    <h1>SKUs para o Produto: {{ $produto->nome }}</h1>

    <a href="{{ route('produtos.skus.create', $produto) }}" class="btn btn-primary">Adicionar SKU</a>

    @if ($skus->count())
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Volume</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($skus as $sku)
            <tr>
                <td>{{ $sku->volume }}</td>
                <td>{{ $sku->preco }}</td>
                <td>
                    <a href="{{ route('produtos.skus.edit', [$produto, $sku]) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('produtos.skus.destroy', [$produto, $sku]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $skus->links() }}
    @else
    <p>Nenhum SKU encontrado.</p>
    @endif
</div>
@endsection