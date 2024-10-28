<div class="bg-black h-2"></div>
<header class="bg-vermelho-asteca py-8 relative shadow-lg z-[9999]">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="/">
                <img src="{{ asset('assets/astecahinomoto_logo.png') }}" alt="Logo Asteca Hinomoto" class="w-24">
            </a>
        </div>

        <!-- Navegação -->
        <nav class="flex space-x-8 items-center">
            <!-- Sobre a Asteca -->
            <a href="{{ route('sobre') }}"
                class="text-white text-lg relative group inline-block {{ Request::is('sobre') ? 'text-black font-semibold' : '' }}">
                <span class="font-normal group-hover:opacity-0 transition-opacity duration-200">
                    Sobre a Asteca
                </span>
                <span class="font-semibold absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                    Sobre a Asteca
                </span>
                @if (Request::is('sobre'))
                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-3 w-2 h-2 bg-black rounded-full"></span>
                @endif
            </a>

            <!-- Produtos com Submenu Expansível -->
            <div>
                <button id="produtos-menu"
                    class="text-white text-lg relative group inline-flex items-center {{ Request::is('produtos') ? 'text-black font-semibold' : '' }}">
                    <span class="font-normal group-hover:opacity-0 transition-opacity duration-200 flex items-center">
                        Produtos
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                    <span class="font-semibold absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap flex items-center">
                        Produtos
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                    @if (Request::is('produtos'))
                    <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-3 w-2 h-2 bg-black rounded-full"></span>
                    @endif
                </button>
            </div>

            <!-- Notícias -->
            <a href="{{ route('noticias.index') }}"
                class="text-white text-lg relative group inline-block {{ Request::is('noticias.index') ? 'text-black font-semibold' : '' }}">
                <span class="font-normal group-hover:opacity-0 transition-opacity duration-200">
                    Notícias
                </span>
                <span class="font-semibold absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                    Notícias
                </span>
                @if (Request::is('noticias.index'))
                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-3 w-2 h-2 bg-black rounded-full"></span>
                @endif
            </a>

            <!-- Receitas -->
            <a href="{{ route('receitas.index') }}"
                class="text-white text-lg relative group inline-block {{ Request::is('receitas') ? 'text-black font-semibold' : '' }}">
                <span class="font-normal group-hover:opacity-0 transition-opacity duration-200">
                    Receitas
                </span>
                <span class="font-semibold absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                    Receitas
                </span>
                @if (Request::is('receitas'))
                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-3 w-2 h-2 bg-black rounded-full"></span>
                @endif
            </a>

            <!-- Fale com a Asteca -->
            <a href="{{ url('/fale-conosco') }}"
                class="text-white text-lg relative group inline-block {{ Request::is('fale-conosco') ? 'text-black font-semibold' : '' }}">
                <span class="font-normal group-hover:opacity-0 transition-opacity duration-200">
                    Fale com a Asteca
                </span>
                <span class="font-semibold absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                    Fale com a Asteca
                </span>
                @if (Request::is('fale-conosco'))
                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-3 w-2 h-2 bg-black rounded-full"></span>
                @endif
            </a>
        </nav>

        <!-- Botões e Ícones -->
        <div class="flex items-center space-x-4">
            <a href="#" class="bg-black hover:bg-red-900 text-white font-semibold px-6 py-3 flex items-center">
                <i class="fas fa-shopping-cart mr-2"></i>
                Contato para Pedido
            </a>
            <button class="text-black">
                <i class="fas fa-search text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Submenu Expansível de Produtos que ocupa 100% da largura -->
    <div id="produtos-submenu" class="absolute left-0 w-screen bg-red-700 text-black py-6 hidden transition-all duration-300 ease-in-out top-full">
        <div class="flex justify-between w-full max-w-screen-xl mx-auto px-10">
            <!-- Coluna de Categorias -->
            <div class="grid grid-cols-2 gap-8 w-2/3">
                @foreach($tiposHeader as $tipo)
                <div class="flex items-center space-x-3" data-tipo-descricao="{{ $tipo->descricao }}" data-tipo-imagem="{{ asset('images/' . $tipo->slug . '_highlight.png') }}">
                    <img src="{{ asset('images/' . $tipo->slug . '_icon.png') }}" alt="{{ $tipo->nome }}" class="w-10 h-10">
                    <a href="{{ route('marcas.tipo', $tipo->slug) }}" class="text-black font-medium">{{ $tipo->nome }}</a>
                </div>
                @endforeach
            </div>

            <!-- Coluna de Destaque -->
            <div class="w-1/3 flex items-center bg-yellow-400 rounded-lg px-6 py-4" id="descricao-tipo">
                <img src="{{ asset('images/default_highlight.png') }}" alt="Descrição" id="descricao-tipo-imagem" class="w-24 h-24 rounded-md mr-4">
                <div>
                    <h3 class="text-lg font-semibold" id="descricao-tipo-nome">Selecione uma categoria</h3>
                    <p class="text-sm" id="descricao-tipo-descricao">Passe o mouse sobre as categorias para mais detalhes</p>
                </div>
            </div>
        </div>
    </div>
</header>

@vite('resources/js/submenu.js')