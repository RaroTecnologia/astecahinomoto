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
            <p class="text-lg text-white mt-4">Asteca Hinomoto: Sabores que conectam Brasil e Japão.</p>
        </div>
    </div>
</div>

<!-- Imagem Grande com Posicionamento -->
<div class="relative">
    <div class="absolute inset-x-0 top-[-120px] mx-auto max-w-5xl">
        <div class="relative w-full" style="padding-top: 56.25%"> <!-- Aspect ratio 16:9 -->
            <iframe
                src="https://www.youtube.com/embed/Pw1nWRLSnEs"
                class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <p class="text-center text-sm text-gray-600 mt-2">Conheça a Asteca Hinomoto</p>
    </div>
</div>

<!-- Bloco de Texto Adicional -->
<div class="container mx-auto mt-48 py-12 px-4 pt-96">
    <div class="text-center">
        <h2 class="text-3xl font-bold mb-6">Nossa História</h2>
        <p class="text-gray-600 leading-relaxed max-w-3xl mx-auto">
            Fundada em Presidente Prudente, no dia 28 de junho de 1948, pelos imigrantes japoneses Sr. Keniti Fukuhara e Sr. Massami Honda, a empresa Asteca Hinomoto logo demonstrou sinais de prosperidade. Isso porque seus proprietários sempre cultivaram valores tradicionais da cultura japonesa, como: ética, trabalho árduo, conhecimento e inovação. Desde então, a marca Asteca Hinomoto se consolidou no mercado com a qualidade de suas matérias primas e moderna tecnologia na produção de seus produtos.A empresa é especializada nos setores alimentício e de bebidas, com fábricas distintas para os diferentes segmentos. Com o sucesso de seus produtos, a empresa alcançou forte presença nos pontos de vendas do Brasil e ultrapassou as fronteiras, exportando para os principais mercados internacionais, como: Estados Unidos, França, Holanda, Itália, Portugal, Japão, China, países do Mercosul, dentre outros. Fazendo valer os princípios dos seus fundadores, a Asteca Hinomoto permanece atuante na inovação de sua linha, para continuar participando do dia-a-dia dos brasileiros com deliciosos e variados produtos. </p>
    </div>
</div>

<!-- Primeiro Bloco de Imagem e Conteúdo -->
<div class="container mx-auto py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Imagem -->
        <div class="max-w-xl mx-auto">
            <img src="assets/img1_sobre.png"
                alt="Produtos Asteca Hinomoto"
                class="rounded-lg w-full shadow-lg object-cover">
        </div>
        <!-- Texto -->
        <div class="flex items-center">
            <p class="text-gray-600 leading-relaxed">
                <span class="font-light text-2xl">A empresa é especializada nos setores alimentício e de bebidas, com fábricas distintas para os diferentes segmentos.</span>
                <br><br>O sucesso de seus produtos, a empresa alcançou forte presença nos pontos de vendas do Brasil e ultrapassou as fronteiras, exportando para os principais mercados internacionais, como: Estados Unidos, França, Holanda, Itália, Portugal, Japão, China, países do Mercosul, dentre outros. Fazendo valer os princípios dos seus fundadores, a Asteca Hinomoto permanece atuante na inovação de sua linha, para continuar participando do dia-a-dia dos brasileiros com deliciosos e variados produtos.
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
            <span class="font-light text-2xl">Possuímos uma rede de distribuição cobrindo praticamente todo o território brasileiro, através de nossos representantes de venda e distribuidores estrategicamente localizados. </span> <br><br>Nossos produtos estão presentes nos <span class="font-bold">principais pontos de vendas, redes varejistas e atacadistas do Brasil</span>.

                Atualmente exportamos nossos produtos para: Alemanha, Espanha, Estados Unidos da América, França, Holanda, Itália, Portugal, Angola, Japão, China, Curaçao, países do Mercosul, dentre outros. Contamos hoje com um quadro de colaboradores que se situa em torno de 500 funcionários diretos e 120 representantes de vendas, além de uma frota composta de 80 caminhões entre próprios e terceirizados para distribuição dos produtos. </p>
        </div>
        <!-- Imagem -->
        <div class="max-w-xl mx-auto">
            <img src="assets/img2_sobre.png"
                alt="Forte Rede de Distribuição"
                class="rounded-lg w-full shadow-lg object-cover">
        </div>
    </div>
</div>

@endsection