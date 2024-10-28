@extends('layouts.app')

@section('title', $linha->nome . ' - ' . $categoriaMarca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        currentPage="{{ $linha->nome }}"
        parentPage="{{ $categoriaMarca->nome }}" />

    <!-- Título da Linha -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">{{ $linha->nome }}</h1>
        <p class="text-gray-600">{{ $linha->descricao }}</p>
    </div>

    <!-- Listagem de Produtos -->
    @if($produtos->isEmpty())
    <p class="text-center text-gray-600">Nenhum produto encontrado nesta linha.</p>
    @else
    <div class="flex flex-wrap justify-center gap-8">
        @foreach($produtos as $produto)
        <div class="border p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
            <div class="flex flex-col h-full">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $produto->nome }}</h2>
                <p class="text-gray-600 flex-grow">{{ $produto->descricao }}</p>
                <a href="{{ route('produtos.show', [$categoriaMarca->slug, $produto->slug]) }}" class="text-red-600 font-semibold hover:underline mt-4 block text-center">Ver mais detalhes</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection