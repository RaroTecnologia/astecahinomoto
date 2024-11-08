@extends('layouts.app')

@section('title', 'Receitas')

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb e Compartilhar -->
    <x-breadcrumb-share
        currentPage="Receitas"
        parentText="Home"
        parentRoute="home" />

    <!-- Título Centralizado -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900">Receitas</h1>
        <p class="mt-4 text-gray-600">Descubra nossas deliciosas receitas</p>
    </div>

    <!-- Autocomplete Search -->
    <x-autocomplete context="receitas" placeholder="receitas" />

    <!-- Filtros e Ordenação -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <!-- Tags de Categorias -->
        <div class="flex flex-wrap gap-2 flex-grow">
            <a href="#"
                data-category=""
                class="category-link px-4 py-2 rounded-full text-sm {{ request()->get('categoria') === null ? 'bg-vermelho-asteca text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Todas
            </a>
            @foreach($categorias as $categoria)
            <a href="#"
                data-category="{{ $categoria->slug }}"
                class="category-link px-4 py-2 rounded-full text-sm {{ request()->get('categoria') === $categoria->slug ? 'bg-vermelho-asteca text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $categoria->nome }}
            </a>
            @endforeach
        </div>

        <!-- Ordenação -->
        <div class="relative mt-4 md:mt-0">
            <button id="orderFilter" class="flex items-center justify-between w-48 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50">
                <span id="selectedOrder">
                    @if(request()->get('order') == 'recent')
                    Mais Recentes
                    @elseif(request()->get('order') == 'likes')
                    Mais Curtidas
                    @else
                    Ordenar por
                    @endif
                </span>
                <i class="fas fa-chevron-down ml-2"></i>
            </button>
            <div id="orderDropdown" class="hidden absolute right-0 z-10 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg">
                <a href="#" data-order="recent" class="block px-4 py-2 hover:bg-gray-100 text-gray-700 {{ request()->get('order') == 'recent' ? 'bg-gray-100' : '' }}">
                    Mais Recentes
                </a>
                <a href="#" data-order="likes" class="block px-4 py-2 hover:bg-gray-100 text-gray-700 {{ request()->get('order') == 'likes' ? 'bg-gray-100' : '' }}">
                    Mais Curtidas
                </a>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-5 rounded-lg flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-vermelho-asteca"></div>
            <span class="text-gray-700 font-medium">Carregando...</span>
        </div>
    </div>

    <!-- Lista de Receitas -->
    <div id="recipes-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($receitas as $receita)
        <x-card-item
            title="{{ $receita->nome }}"
            description="{{ Str::limit($receita->chamada, 100) }}"
            image="{{ $receita->imagem ? asset('storage/receitas/' . $receita->imagem) : 'assets/sem_imagem.png' }}"
            link="{{ route('receitas.show', [
                'categoria' => $receita->categoria ? $receita->categoria->slug : 'sem-categoria',
                'slug' => $receita->slug
            ]) }}"
            linkText="Ver Receita" />
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="mt-8">
        {{ $receitas->links() }}
    </div>
</div>
@endsection
@vite('resources/js/autocomplete.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showSkeletons() {
            const recipesContainer = document.getElementById('recipes-list');
            recipesContainer.style.opacity = '0';
            recipesContainer.style.transition = 'opacity 0.3s ease';

            // Criar grid de skeletons
            const skeletonsHTML = Array(8).fill().map(() => `
                <x-recipe-skeleton />
            `).join('');

            recipesContainer.innerHTML = skeletonsHTML;
            recipesContainer.style.opacity = '1';
        }

        function renderResults(data) {
            const recipesContainer = document.getElementById('recipes-list');
            recipesContainer.style.opacity = '0';

            setTimeout(() => {
                recipesContainer.innerHTML = data.list;

                // Adiciona fade-in para cada card
                const cards = recipesContainer.querySelectorAll('.recipe-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100); // Delay escalonado para cada card
                });

                recipesContainer.style.opacity = '1';
            }, 300);
        }

        function updateRecipes(params = {}) {
            const currentUrl = new URL(window.location.href);

            Object.keys(params).forEach(key => {
                if (params[key]) {
                    currentUrl.searchParams.set(key, params[key]);
                } else {
                    currentUrl.searchParams.delete(key);
                }
            });

            showSkeletons();

            fetch(currentUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    renderResults(data);
                    document.querySelector('.mt-8').innerHTML = data.pagination;
                    window.history.pushState({}, '', currentUrl.toString());

                    // Atualiza classes ativas das categorias com transição
                    document.querySelectorAll('.category-link').forEach(link => {
                        const isActive = link.dataset.category === (params.categoria || '');
                        link.style.transition = 'all 0.3s ease';
                        link.classList.toggle('bg-vermelho-asteca', isActive);
                        link.classList.toggle('text-white', isActive);
                        link.classList.toggle('bg-gray-100', !isActive);
                        link.classList.toggle('text-gray-700', !isActive);
                    });
                })
                .catch(error => console.error('Erro:', error));
        }

        // Setup dos links de categoria
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const category = this.dataset.category;
                updateRecipes({
                    categoria: category
                });
            });
        });

        // Setup dos links de ordenação
        document.querySelectorAll('#orderDropdown a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const order = this.dataset.order;
                updateRecipes({
                    order: order
                });
                document.getElementById('orderDropdown').classList.add('hidden');
            });
        });

        // Setup do dropdown
        function setupDropdown(buttonId, dropdownId) {
            const button = document.getElementById(buttonId);
            const dropdown = document.getElementById(dropdownId);

            if (button && dropdown) {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
            }
        }

        setupDropdown('orderFilter', 'orderDropdown');

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', function() {
            const dropdowns = document.querySelectorAll('#orderDropdown');
            dropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
        });

        // Prevenir fechamento ao clicar dentro do dropdown
        const dropdowns = document.querySelectorAll('#orderDropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>