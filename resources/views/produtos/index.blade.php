@extends('layouts.app')

@section('title', $categoriaMarca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        currentPage="{{ $categoriaMarca->nome }}"
        parentPage="Nossas Marcas"
        :marca="$categoriaTipo" />


    <h1 class="text-4xl font-bold">Produtos da Marca {{ $categoriaMarca->nome }}</h1>

    @if($produtos->isEmpty())
    <p>Nenhum produto encontrado</p>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($produtos as $produto)
        <div class="border p-4 rounded-lg">
            <h2 class="text-xl font-semibold">{{ $produto->nome }}</h2>
            <p>{{ $produto->descricao }}</p>
            <a href="{{ route('produtos.show', [$categoriaMarca->slug, $produto->slug]) }}" class="text-red-600 font-semibold hover:underline">Ver mais detalhes</a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection