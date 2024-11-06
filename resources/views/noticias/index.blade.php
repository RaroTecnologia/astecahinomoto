@extends('layouts.app')

@section('title', 'Notícias')

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb e Compartilhar -->
    <x-breadcrumb-share
        currentPage="Notícias"
        parentText="Home"
        parentRoute="home" />

    <!-- Título Centralizado -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900">Notícias</h1>
        <p class="mt-4 text-gray-600">Fique por dentro das últimas novidades</p>
    </div>

    <!-- Autocomplete Search -->
    <x-autocomplete context="noticias" placeholder="notícias" />

    <!-- Filtros e Ordenação -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <!-- Tags de Categorias -->
        <div class="flex flex-wrap gap-2 flex-grow">
            <a href="#"
                data-category=""
                class="category-link px-4 py-2 rounded-full text-sm {{ !request()->get('categoria') ? 'bg-vermelho-asteca text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Todas
                <span class="ml-1 text-xs">({{ $noticias->total() }})</span>
            </a>
            @foreach($categorias as $categoria)
            <a href="#"
                data-category="{{ $categoria->slug }}"
                class="category-link px-4 py-2 rounded-full text-sm {{ request()->get('categoria') == $categoria->slug ? 'bg-vermelho-asteca text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
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
                    @elseif(request()->get('order') == 'views')
                    Mais Visualizadas
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
                <a href="#" data-order="views" class="block px-4 py-2 hover:bg-gray-100 text-gray-700 {{ request()->get('order') == 'views' ? 'bg-gray-100' : '' }}">
                    Mais Visualizadas
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Notícias -->
    <div id="news-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($noticias as $noticia)
        <x-card-item
            title="{{ $noticia->titulo }}"
            description="{{ Str::limit($noticia->resumo, 100) }}"
            image="{{ $noticia->imagem_url }}"
            link="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}"
            linkText="Ler Mais" />
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="mt-8">
        {{ $noticias->links() }}
    </div>
</div>

@vite('resources/js/autocomplete.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showSkeletons() {
            const newsContainer = document.getElementById('news-list');
            newsContainer.style.opacity = '0';
            newsContainer.style.transition = 'opacity 0.3s ease';

            const skeletonsHTML = Array(12).fill().map(() => `
                <x-recipe-skeleton />
            `).join('');

            newsContainer.innerHTML = skeletonsHTML;
            newsContainer.style.opacity = '1';
        }

        function updateNews(params = {}) {
            const currentUrl = new URL(window.location.href);
            const currentCategory = currentUrl.searchParams.get('categoria');

            if (!params.hasOwnProperty('categoria')) {
                params.categoria = currentCategory;
            }

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
                    const newsContainer = document.getElementById('news-list');
                    newsContainer.style.opacity = '0';

                    setTimeout(() => {
                        newsContainer.innerHTML = data.list;

                        const cards = newsContainer.querySelectorAll('.recipe-card');
                        cards.forEach((card, index) => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, index * 100);
                        });

                        newsContainer.style.opacity = '1';
                        document.querySelector('.mt-8').innerHTML = data.pagination;
                        window.history.pushState({}, '', currentUrl.toString());

                        document.querySelectorAll('.category-link').forEach(link => {
                            const categorySlug = link.dataset.category;
                            const isActive = categorySlug === (currentUrl.searchParams.get('categoria') || '');

                            link.classList.toggle('bg-vermelho-asteca', isActive);
                            link.classList.toggle('text-white', isActive);
                            link.classList.toggle('bg-gray-100', !isActive);
                            link.classList.toggle('text-gray-700', !isActive);
                        });
                    }, 300);
                })
                .catch(error => console.error('Erro:', error));
        }

        // Setup dos links de categoria
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const category = this.dataset.category;
                updateNews({
                    categoria: category
                });
            });
        });

        // Setup do dropdown de ordenação
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

        // Ordenação
        document.querySelectorAll('#orderDropdown a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const order = this.dataset.order;
                updateNews({
                    order: order
                });
                document.getElementById('orderDropdown').classList.add('hidden');
            });
        });

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
@endsection