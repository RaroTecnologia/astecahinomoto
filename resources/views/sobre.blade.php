@extends('layouts.app')

@section('title', 'Sobre a Asteca Hinomoto')

@section('content')
<div class="relative bg-vermelho-asteca pb-24">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb e Opção de Compartilhar -->
        <x-breadcrumb-share currentPage="Sobre a Asteca Hinomoto" textColor="text-red-200" />

        <!-- Título e Subtítulo -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-black">Nossa História</h1>
            <p class="text-lg text-white mt-4">Proporcionar saúde e bem-estar para todos os brasileiros.</p>
        </div>
    </div>
</div>

<!-- Imagem Grande com Posicionamento -->
<div class="relative">
    <div class="absolute inset-x-0 top-[-120px] mx-auto max-w-5xl">
        <img src="https://via.placeholder.com/1920x1080?text=Imagem+da+Empresa" alt="Imagem da Empresa" class="rounded-lg shadow-lg w-full">
        <p class="text-center text-sm text-gray-600 mt-2">Nossa sede em São Paulo - Um símbolo do nosso compromisso com a inovação e qualidade</p>
    </div>
</div>

<!-- Bloco de Texto Adicional -->
<div class="container mx-auto mt-48 py-12 px-4 pt-96">
    <div class="text-center">
        <h2 class="text-3xl font-bold mb-6">Nossa História</h2>
        <p class="text-gray-600 leading-relaxed max-w-3xl mx-auto">
            A Asteca Hinomoto começou sua jornada em [ano], com o objetivo de trazer inovação e qualidade em produtos para os consumidores brasileiros. Com uma presença forte no mercado, nossa missão é proporcionar bem-estar e saúde, garantindo que nossos produtos sejam de alta qualidade e acessíveis para todos.
        </p>
    </div>
</div>

<!-- Primeiro Bloco de Imagem e Conteúdo -->
<div class="container mx-auto py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Imagem -->
        <div>
            <img src="https://via.placeholder.com/600x400?text=Imagem+Complementar" alt="Imagem Complementar" class="rounded-lg w-full shadow-lg">
        </div>
        <!-- Texto -->
        <div class="flex items-center">
            <p class="text-gray-600 leading-relaxed">
                Ao longo dos anos, a Asteca Hinomoto expandiu sua atuação e continua a se reinventar para atender às necessidades dos consumidores, sempre com foco em sustentabilidade, inovação, e bem-estar.
            </p>
        </div>
    </div>
</div>

<!-- Segundo Bloco de Imagem e Conteúdo (Invertido) -->
<div class="container mx-auto py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Texto -->
        <div class="flex items-center">
            <p class="text-gray-600 leading-relaxed">
                Nossa empresa se destaca pela busca constante de excelência em produtos e serviços. Investimos em pesquisa e desenvolvimento para oferecer soluções inovadoras que melhoram a qualidade de vida dos nossos clientes, sempre respeitando o meio ambiente e promovendo práticas sustentáveis.
            </p>
        </div>
        <!-- Imagem -->
        <div>
            <img src="https://via.placeholder.com/600x400?text=Outra+Imagem+Complementar" alt="Outra Imagem Complementar" class="rounded-lg w-full shadow-lg">
        </div>
    </div>
</div>

@endsection