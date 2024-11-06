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
                <button
                    id="botaoCurtir"
                    class="flex items-center {{ Cookie::has('receita_curtida_' . $receita->id) ? 'text-red-600 pointer-events-none' : 'text-gray-600 hover:text-red-600' }} transition"
                    data-receita-id="{{ $receita->id }}">
                    <i id="iconeCurtir" class="fa-heart mr-1 {{ Cookie::has('receita_curtida_' . $receita->id) ? 'fa-solid' : 'fa-regular' }}"></i>
                    <span id="textoCurtir">{{ Cookie::has('receita_curtida_' . $receita->id) ? 'Curtido' : 'Curtir' }}</span>
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
        <div id="recipes-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($sugestoes as $receita)
            <x-card-item
                title="{{ $receita->nome }}"
                description="{{ Str::limit($receita->chamada, 100) }}"
                image="{{ $receita->imagem ? asset('storage/receitas/' . $receita->imagem) : 'assets/sem_imagem.png' }}"
                link="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
                linkText="Ver Receita" />
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Função compartilhar
        window.compartilharReceita = function() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                const span = document.getElementById('textoCompartilhar');
                span.textContent = 'Link copiado!';
                setTimeout(() => {
                    span.textContent = 'Compartilhar esta receita';
                }, 2000);
            });
        }

        // Configuração do botão curtir
        const botaoCurtir = document.getElementById('botaoCurtir');
        if (botaoCurtir) {
            const receitaId = "{{ $receita->id }}";
            const iconeCurtir = document.getElementById('iconeCurtir');
            const textoCurtir = document.getElementById('textoCurtir');

            // Verifica se já curtiu ao carregar
            if (document.cookie.includes('receita_curtida_' + receitaId)) {
                setEstadoCurtido();
                return; // Não adiciona o evento de clique se já estiver curtido
            }

            // Função para atualizar o estado visual
            function setEstadoCurtido() {
                iconeCurtir.classList.remove('fa-regular');
                iconeCurtir.classList.add('fa-solid');
                textoCurtir.textContent = 'Curtido';
                botaoCurtir.classList.add('text-red-600', 'pointer-events-none');
                botaoCurtir.classList.remove('hover:text-red-600');
            }

            // Adiciona evento de clique apenas se não estiver curtido
            botaoCurtir.addEventListener('click', function() {
                fetch('/receitas/' + receitaId + '/curtir', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const numeroCurtidas = document.getElementById('numeroCurtidas');

                        if (numeroCurtidas) {
                            numeroCurtidas.textContent = `(${data.curtidas})`;
                        }

                        // Animação de curtida bem-sucedida
                        iconeCurtir.classList.remove('fa-regular');
                        iconeCurtir.classList.add('fa-solid');
                        textoCurtir.textContent = 'Curtido!';

                        // Após um tempo, finaliza o estado
                        setTimeout(() => {
                            setEstadoCurtido();
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
            });
        }
    });
</script>
@endsection