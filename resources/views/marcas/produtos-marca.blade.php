@extends('layouts.app')

@section('title', 'Produtos de ' . $marca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share currentPage="{{ $marca->nome }}" parentPage="{{ $tipo->nome }}" />

    <!-- Título da Página -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">Produtos de {{ $marca->nome }}</h1>
        <p class="text-gray-600">{{ $marca->descricao ?? 'Explore os produtos desta marca.' }}</p>
    </div>

    <!-- Listagem de Produtos -->
    <div class="space-y-8">
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($produtos as $produto)
            <a href="{{ route('marcas.produtos.linhas', [$tipo->slug, $marca->slug, $produto->slug]) }}" class="block border p-6 rounded-lg shadow-lg hover:bg-gray-50 transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
                <div class="flex flex-col h-full">
                    <!-- Adicionando a Imagem do Produto -->
                    <img src="{{ $produto->imagem ? asset('storage/produtos/' . $produto->imagem) : asset('assets/sem_imagem.png') }}"
                        alt="{{ $produto->nome }}"
                        class="w-full h-48 object-contain mb-4 rounded">

                    <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $produto->nome }}</h2>
                    <p class="text-gray-600 flex-grow text-center">{{ $produto->descricao ?? 'Descrição indisponível' }}</p>
                    <span class="text-red-600 font-semibold hover:underline mt-4 text-center">Ver mais detalhes</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection