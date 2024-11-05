@extends('layouts.app')

@section('title', 'Linhas de ' . $produtoOuLinha->nome . ' - ' . $marca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        :tipo="$tipo"
        :marca="$marca"
        :linha="$produtoOuLinha"
        currentPage="" />

    <!-- Título da Página -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">Linhas de {{ $produtoOuLinha->nome }}</h1>
        <p class="text-gray-600">{{ $produtoOuLinha->descricao ?? 'Explore as linhas deste produto.' }}</p>
    </div>

    <!-- Listagem de Linhas -->
    <div class="space-y-8">
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($subLinhas as $subLinha)
            <a href="{{ route('marcas.produtos.linhas', [$tipo->slug, $marca->slug, $produtoOuLinha->slug, $subLinha->slug]) }}" class="block border p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
                <div class="flex flex-col h-full">
                    <img src="{{ $subLinha->imagem ? asset('storage/categorias/' . $subLinha->imagem) : asset('assets/sem_imagem.png') }}" alt="{{ $subLinha->nome }}" class="w-full h-56 object-cover mb-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $subLinha->nome }}</h2>
                    <p class="text-gray-600 flex-grow text-center">{{ $subLinha->descricao ?? 'Descrição indisponível' }}</p>
                    <span class="text-red-600 font-semibold hover:underline mt-4 text-center">Ver mais detalhes</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection