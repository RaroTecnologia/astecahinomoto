@extends('layouts.app')

@section('title', 'Receitas')

@section('content')
<div class="relative bg-white pb-24">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb e Opção de Compartilhar -->
        <x-breadcrumb-share currentPage="Receitas" />

        <!-- Título da Página -->
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-900">Receitas</h1>
        </div>

        <!-- Filtros e Ordenação -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="text-gray-600 text-sm"><span id="count">{{ $receitas->total() }}</span> Artigos Encontrados</span>
                <span class="mx-2 text-gray-400">|</span>
                <a href="#" id="clear-filters" class="text-red-600 font-semibold text-sm">Limpar Filtros X</a>
            </div>
            <div class="flex items-center">
                <button class="flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M3 12h18m-7 6h7"></path>
                    </svg>
                    Ordenar
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Filtrar por:</h3>
            <div class="flex space-x-2" id="categories">
                @foreach($categorias as $categoria)
                <a href="javascript:void(0);" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-red-600 hover:text-white transition" data-slug="{{ $categoria->slug }}">
                    {{ $categoria->nome }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Campo de busca com autocomplete -->
        <x-autocomplete context="receitas" placeholder="Receitas" />

        <!-- Listagem de Receitas -->
        <div id="recipes-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($receitas as $receita)
            <x-card-item
                title="{{ $receita->nome }}"
                description="{{ Str::limit($receita->chamada, 100) }}"
                image="{{ $receita->imagem ? asset('storage/receitas/' . $receita->imagem) : 'assets/sem_imagem.png' }}"
                link="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
                linkText="Ler Mais" />
            @endforeach
        </div>

        <!-- Paginação -->
        <div id="pagination" class="mt-8">
            {{ $receitas->links() }}
        </div>

    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script carregado e DOM pronto'); // Verifique se o script foi carregado

        // Capturar clique nas categorias
        document.querySelectorAll('#categories a').forEach(function(categoryLink) {
            categoryLink.addEventListener('click', function(e) {
                e.preventDefault();

                let slug = this.getAttribute('data-slug');
                console.log(`Categoria clicada: ${slug}`); // Verifique se o clique está sendo capturado

                // Fazer a requisição AJAX para a filtragem
                fetch(`/receitas/filtrar/${slug}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Dados recebidos da API:', data); // Verifique se os dados estão sendo recebidos corretamente

                        // Atualizar a contagem
                        document.getElementById('count').innerText = data.count;

                        // Atualizar a listagem de receitas
                        document.getElementById('recipes-list').innerHTML = data.receitas;

                        // Atualizar a paginação
                        document.getElementById('pagination').innerHTML = data.pagination;
                    })
                    .catch(error => {
                        console.error('Erro ao filtrar as receitas:', error);
                    });
            });
        });

        // Limpar os filtros
        document.getElementById('clear-filters').addEventListener('click', function(e) {
            e.preventDefault();

            // Recarregar a página para exibir todas as receitas
            window.location.reload();
        });

        // Paginação via AJAX
        document.addEventListener('click', function(e) {
            if (e.target.closest('#pagination a')) {
                e.preventDefault();
                let url = e.target.getAttribute('href');

                // Fazer a requisição AJAX para a paginação
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Dados da paginação:', data); // Verifique se os dados da paginação estão corretos

                        // Atualizar a contagem
                        document.getElementById('count').innerText = data.count;

                        // Atualizar a listagem de receitas
                        document.getElementById('recipes-list').innerHTML = data.receitas;

                        // Atualizar a paginação
                        document.getElementById('pagination').innerHTML = data.pagination;
                    })
                    .catch(error => {
                        console.error('Erro ao paginar:', error);
                    });
            }
        });
    });
</script>