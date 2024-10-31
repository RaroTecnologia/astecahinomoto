@extends('layouts.app')

@section('title', 'Nossas Marcas')

@section('content')

<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share currentPage="Nossas Marcas" />

    <!-- Título da Página -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-700">Nossas Marcas</h1>
        <p class="text-gray-700 mt-2">Explore nossas categorias principais</p>
    </div>

    <!-- Listagem das Categorias Principais -->
    <div class="space-y-8">
        @foreach($tipos as $tipo)
        <a href="{{ route('marcas.tipo', $tipo->slug) }}"
            class="block mb-6 transition-transform hover:scale-[1.02]">
            <div class="relative rounded-3xl overflow-hidden flex items-center p-6"
                style="background-color: #{{ $tipo->cor_bg }}">
                <!-- Conteúdo do Tipo -->
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-{{ $tipo->cor_texto }}">
                        {{ strtoupper($tipo->nome) }}
                    </h2>
                    @if($tipo->descricao)
                    <p class="text-{{ $tipo->cor_texto }} opacity-90 mt-2">
                        {{ $tipo->descricao }}
                    </p>
                    @endif
                    <div class="text-{{ $tipo->cor_texto }} opacity-80 mt-3">
                        {{ $tipo->categorias_count }} {{ Str::plural('marca', $tipo->categorias_count) }}
                    </div>
                </div>

                <!-- Imagem do Tipo -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/categorias/' . $tipo->slug . '.png') }}"
                        alt="{{ $tipo->nome }}"
                        class="w-40 h-40 object-cover">
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

@endsection