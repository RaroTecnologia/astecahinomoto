@extends('layouts.app')

@section('title', $receita->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <x-breadcrumb-share
            currentPage="{{ $receita->nome }}"
            parentRoute="receitas.index"
            parentText="Receitas" />
    </div>

    <!-- Título da Receita -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Imagem da Receita -->
        <div class="relative">
            <img src="{{ $receita->imagem ? asset('storage/receitas/' . $receita->imagem) : 'assets/sem_imagem.png' }}" alt="{{ $receita->nome }}" class="w-full rounded-lg shadow-lg object-cover">

            <!-- Botões de Compartilhar e Curtir -->
            <div class="flex space-x-4 mt-4">
                <button onclick="compartilharReceita()" class="flex items-center text-gray-600 hover:text-red-600 transition">
                    <i class="fa-regular fa-share-from-square mr-1"></i>
                    <span id="textoCompartilhar">Compartilhar esta receita</span>
                </button>
                <button onclick="curtirReceita({{ $receita->id }})" class="flex items-center text-gray-600 hover:text-red-600 transition">
                    <i id="iconeCurtir" class="fa-regular fa-heart mr-1"></i>
                    <span id="textoCurtir">Curtir</span>
                    <span id="numeroCurtidas" class="ml-1">({{ $receita->curtidas }})</span>
                </button>
            </div>
        </div>

        <!-- Conteúdo da Receita -->
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $receita->nome }}</h1>

            <!-- Informações de Dificuldade e Tempo de Preparo -->
            <div class="flex items-center mb-6">
                <span class="text-sm text-gray-600">Dificuldade:</span>
                <span class="ml-2 text-red-600 font-semibold">{{ $receita->dificuldade }}</span>
                <span class="mx-2 text-gray-400">•</span>
                <span class="text-sm text-gray-600">Tempo de Preparo:</span>
                <span class="ml-2 text-red-600 font-semibold flex items-center">
                    <i class="fas fa-clock mr-1"></i> {{ $receita->tempo_preparo }} min
                </span>
            </div>

            <!-- Ingredientes -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Ingredientes</h3>
                <div class="ql-editor">{!! $receita->ingredientes !!}</div>
            </div>

            <!-- Modo de Preparo -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Modo de Preparo</h3>
                <div class="ql-editor">{!! $receita->modo_preparo !!}</div>
            </div>
        </div>
    </div>

    <!-- Sugestões de Receitas -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Experimente Também</h2>
        <x-receitas-list :receitas="$sugestoes" />
    </div>
</div>

<script>
    function compartilharReceita() {
        // Pega a URL atual
        const url = window.location.href;

        // Copia para a área de transferência
        navigator.clipboard.writeText(url).then(() => {
            // Atualiza o texto do botão
            const span = document.getElementById('textoCompartilhar');
            span.textContent = 'Link copiado!';

            // Volta ao texto original após 2 segundos
            setTimeout(() => {
                span.textContent = 'Compartilhar esta receita';
            }, 2000);
        });
    }

    function curtirReceita(receitaId) {
        fetch(`/receitas/${receitaId}/curtir`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Atualiza o contador de curtidas
                document.getElementById('numeroCurtidas').textContent = `(${data.curtidas})`;

                if (data.error) {
                    // Caso já tenha curtido
                    document.getElementById('textoCurtir').textContent = 'Já curtido';
                    document.getElementById('iconeCurtir').classList.remove('fa-regular');
                    document.getElementById('iconeCurtir').classList.add('fa-solid');
                } else {
                    // Curtida com sucesso
                    document.getElementById('textoCurtir').textContent = 'Curtido!';
                    document.getElementById('iconeCurtir').classList.remove('fa-regular');
                    document.getElementById('iconeCurtir').classList.add('fa-solid');
                }

                // Volta ao estado original após 2 segundos (mantendo o ícone preenchido)
                setTimeout(() => {
                    document.getElementById('textoCurtir').textContent = 'Curtir';
                }, 2000);
            })
            .catch(error => {
                console.error('Erro ao curtir:', error);
            });
    }

    // Verifica se já curtiu ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const receitaId = {
            {
                $receita - > id
            }
        };
        if (document.cookie.includes(`receita_curtida_${receitaId}`)) {
            document.getElementById('iconeCurtir').classList.remove('fa-regular');
            document.getElementById('iconeCurtir').classList.add('fa-solid');
        }
    });
</script>
@endsection