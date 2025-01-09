<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Acesso Restrito</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen relative">
    <!-- Background com overlay vermelho -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('assets/bg_pre-home.webp') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-vermelho-asteca/45"></div>
    </div>

    <!-- Conteúdo centralizado -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
        <div class="bg-white/10 backdrop-blur-md p-8 rounded-lg border border-vermelho-asteca max-w-xl w-full mx-auto">
            <!-- Logo -->
            <img src="{{ asset('assets/astecahinomoto_logo.png') }}" 
                 alt="Logo Asteca Hinomoto" 
                 class="w-24 mb-12">

            <!-- Texto -->
            <div class="space-y-6 text-white">
                <h1 class="text-4xl font-bold leading-tight">Acesso Restrito</h1>
                
                <div class="space-y-4 text-lg">
                    <p>Desculpe, mas você precisa ter mais de 18 anos para acessar este site.</p>
                    
                    <p>A Asteca Hinomoto é uma empresa que trabalha com a fabricação e distribuição de bebidas alcoólicas, e de acordo com a legislação brasileira, o acesso a conteúdo relacionado a bebidas alcoólicas é restrito a maiores de 18 anos.</p>
                </div>

                <div class="mt-8 text-sm opacity-75">
                    <p>Lei nº 8.069/1990 - Estatuto da Criança e do Adolescente</p>
                    <p>Lei nº 13.106/2015 - Criminalização de venda de bebidas alcoólicas para menores</p>
                </div>
            </div>

            <!-- Botão de Retorno -->
            <div class="mt-12">
                <button onclick="window.location.href='/verificar-idade'" class="w-full bg-white text-vermelho-asteca py-3 px-6 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Voltar para Verificação de Idade
                </button>
            </div>
        </div>
    </div>
</body>
</html> 