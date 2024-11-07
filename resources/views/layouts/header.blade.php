<!-- Header Mobile -->
<header class="block lg:hidden fixed left-0 w-full shadow-lg z-[9999]">
    <div class="bg-black h-2"></div>
    <div class="bg-vermelho-asteca py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="/">
                <img src="{{ asset('assets/astecahinomoto_logo.png') }}" alt="Logo Asteca Hinomoto" class="w-20">
            </a>

            <!-- Botão Hamburger -->
            <button id="mobile-menu-button" class="text-white text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Menu Mobile (inicialmente oculto) -->
    <div id="mobile-menu" class="hidden fixed top-[4.5rem] left-0 w-full bg-vermelho-asteca z-[9998] h-screen overflow-y-auto">
        <nav class="container mx-auto px-4 py-4">
            <a href="{{ route('sobre') }}" class="block py-3 text-white text-lg {{ Request::is('sobre') ? 'font-semibold' : '' }}">
                Sobre a Asteca
            </a>

            <!-- Novo botão dropdown para Produtos -->
            <div class="relative">
                <button class="w-full flex justify-between items-center py-3 text-white text-lg" id="mobile-produtos-btn">
                    Produtos
                    <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div id="mobile-produtos-dropdown" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-red-800 rounded-md">
                    <div class="px-2">
                        @foreach($tiposHeader as $tipo)
                        <a href="{{ route('marcas.tipo', $tipo->slug) }}" class="flex items-center space-x-3 px-4 py-3 text-white hover:bg-red-900">
                            <img src="{{ asset('images/' . $tipo->slug . '_icon.png') }}" alt="{{ $tipo->nome }}" class="w-6 h-6">
                            <span>{{ $tipo->nome }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Resto do menu continua igual -->
            <a href="{{ route('noticias.index') }}" class="block py-3 text-white text-lg {{ Request::is('noticias.index') ? 'font-semibold' : '' }}">
                Notícias
            </a>
            <a href="{{ route('receitas.index') }}" class="block py-3 text-white text-lg {{ Request::is('receitas') ? 'font-semibold' : '' }}">
                Receitas
            </a>
            <a href="{{ url('/fale-conosco') }}" class="block py-3 text-white text-lg {{ Request::is('fale-conosco') ? 'font-semibold' : '' }}">
                Fale com a Asteca
            </a>
            <a href="#" class="block py-3 text-white text-lg">
                <i class="fas fa-phone mr-4"></i>
                Contato para Pedido
            </a>
        </nav>
    </div>
</header>

<!-- Header Desktop (header original) -->
<div class="hidden lg:block">
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
                    <i class="fas fa-phone mr-4"></i>
                    Contato para Pedido
                </a>
                <button class="text-black">
                    <i class="fas fa-search text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Submenu Expansível de Produtos -->
        <div id="produtos-submenu" class="absolute left-0 right-0 bg-red-700 text-black py-6 hidden transition-all duration-300 ease-in-out top-full overflow-hidden">
            <div class="container mx-auto px-4 lg:px-10">
                <div class="flex flex-wrap justify-between">
                    <!-- Coluna de Categorias -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full lg:w-2/3">
                        @foreach($tiposHeader as $tipo)
                        <div class="flex items-center space-x-3" data-tipo-descricao="{{ $tipo->descricao }}" data-tipo-imagem="{{ asset('assets/' . $tipo->slug . '_destaque.png') }}">
                            <img src="{{ asset('assets/' . $tipo->slug . '_icon.png') }}" alt="{{ $tipo->nome }}" class="w-10 h-10">
                            <a href="{{ route('marcas.tipo', $tipo->slug) }}" class="text-black font-medium">{{ $tipo->nome }}</a>
                        </div>
                        @endforeach
                    </div>

                    <!-- Coluna de Destaque -->
                    <div class="w-full lg:w-1/3 flex items-center bg-yellow-400 rounded-lg px-4 py-4 mt-8 lg:mt-0" id="descricao-tipo">
                        <img src="{{ asset('images/default_highlight.png') }}" alt="Descrição" id="descricao-tipo-imagem" class="w-24 h-24 rounded-md mr-6">
                        <div>
                            <h3 class="text-lg font-semibold" id="descricao-tipo-nome">Selecione uma categoria</h3>
                            <p class="text-sm" id="descricao-tipo-descricao">Passe o mouse sobre as categorias para mais detalhes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>

<!-- Script para o menu mobile -->
<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = this.querySelector('i');

        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            menuIcon.classList.remove('fa-bars');
            menuIcon.classList.add('fa-times');
        } else {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('fa-times');
            menuIcon.classList.add('fa-bars');
        }
    });

    // Script atualizado para o dropdown de produtos com animação
    document.getElementById('mobile-produtos-btn').addEventListener('click', function() {
        const dropdown = document.getElementById('mobile-produtos-dropdown');
        const arrow = this.querySelector('svg');
        const dropdownContent = dropdown.querySelector('div');

        if (dropdown.style.maxHeight) {
            dropdown.style.maxHeight = null;
            arrow.style.transform = '';
        } else {
            dropdown.style.maxHeight = dropdownContent.scrollHeight + "px";
            arrow.style.transform = 'rotate(180deg)';
        }
    });
</script>

@vite('resources/js/submenu.js')