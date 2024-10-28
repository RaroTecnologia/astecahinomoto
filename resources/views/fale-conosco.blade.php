@extends('layouts.app')

@section('title', 'Fale Conosco')

@section('content')
<div class="bg-white">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb e Opção de Compartilhar -->
        <x-breadcrumb-share currentPage="Fale Conosco" />

        <!-- Título e Subtítulo -->
        <div class="text-center mb-4">
            <h1 class="text-4xl font-bold text-gray-900">Fale Conosco</h1>
            <p class="text-lg text-gray-700 p-4 mt-4">Estamos sempre prontos para ouvir você, tirar suas dúvidas e fornecer as informações de que você precisa. <br> Escolha abaixo com qual setor você quer falar.</p>
        </div>
    </div>
</div>

<!-- Blocos para Seleção de Setores -->
<div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Setor 1 -->
    <div class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl text-center">
        <i class="fas fa-headset text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Atendimento ao Cliente</h3>
        <p class="text-gray-600 mb-6">Entre em contato para dúvidas gerais e suporte técnico.</p>
        <a href="#" class="text-yellow-500 font-semibold">Entrar em contato</a>
    </div>

    <!-- Setor 2 -->
    <div class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl text-center">
        <i class="fas fa-handshake text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Representantes Comerciais</h3>
        <p class="text-gray-600 mb-6">Quer ser um de nossos representantes? Fale conosco.</p>
        <a href="#" class="text-yellow-500 font-semibold">Entrar em contato</a>
    </div>

    <!-- Setor 3 -->
    <div class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl text-center">
        <i class="fas fa-newspaper text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Imprensa</h3>
        <p class="text-gray-600 mb-6">Para jornalistas e veículos de comunicação.</p>
        <a href="#" class="text-yellow-500 font-semibold">Entrar em contato</a>
    </div>

    <!-- Setor 4 -->
    <div class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl text-center">
        <i class="fas fa-users text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Recursos Humanos</h3>
        <p class="text-gray-600 mb-6">Gostaria de fazer parte do nosso time? Converse com o RH.</p>
        <a href="#" class="text-yellow-500 font-semibold">Entrar em contato</a>
    </div>

    <!-- Setor 5 -->
    <div class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl text-center">
        <i class="fas fa-file-invoice-dollar text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Financeiro</h3>
        <p class="text-gray-600 mb-6">Para questões financeiras e faturamento.</p>
        <a href="#" class="text-yellow-500 font-semibold">Entrar em contato</a>
    </div>

    <!-- Setor 6 -->
    <div class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl text-center">
        <i class="fas fa-question-circle text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Outros Assuntos</h3>
        <p class="text-gray-600 mb-6">Para qualquer outro tipo de solicitação.</p>
        <a href="#" class="text-yellow-500 font-semibold">Entrar em contato</a>
    </div>
</div>

@endsection