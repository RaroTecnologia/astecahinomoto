<footer class="bg-vermelho-asteca text-white py-8 mt-16">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-start space-y-8 md:space-y-0">
        <!-- Seção de logo e texto -->
        <div class="w-full md:w-1/4 text-center md:text-left">
            <img src="{{ asset('assets/astecahinomoto_since.png') }}" alt="Asteca Hinomoto Since 1948" class="w-12 mb-4 mx-auto md:mx-0">
            @php
            $anoFundacao = 1948;
            $anoAtual = date('Y');
            $idade = $anoAtual - $anoFundacao;
            @endphp
            <p class="text-sm md:w-48 lg:w-56 xl:w-64 mx-auto md:mx-0">
                <span class="block font-bold">Asteca Hinomoto.</span>
                <span class="block">{{ $idade }} anos de ética,</span>
                <span class="block">trabalho e dedicação.</span>
            </p>
        </div>

        <!-- Links de navegação -->
        <div class="w-full md:w-1/2 flex flex-col md:flex-row justify-between text-center md:text-left">
            <!-- Links principais à esquerda -->
            <div class="mb-4 md:mb-0">
                <ul class="space-y-2">
                    <li><a href="{{ route('sobre') }}" class="text-white hover:underline">Sobre a Asteca</a></li>
                    <li><a href="{{ route('marcas') }}" class="text-white hover:underline">Nossas Marcas</a></li>
                    <li><a href="{{ route('receitas.index') }}" class="text-white hover:underline">Receitas</a></li>
                    <li><a href="{{ route('noticias.index') }}" class="text-white hover:underline">Notícias</a></li>
                    <li><a href="/fale-conosco" class="text-white hover:underline">Fale Conosco</a></li>
                    <li><a href="{{ route('politica-privacidade') }}" class="text-white hover:underline">Política de Privacidade</a></li>
                </ul>
            </div>

            <!-- Catálogo em destaque à direita -->
            <div class="flex flex-col items-center md:items-end">
                <a href="{{ route('catalogo.index') }}"
                    class="group relative inline-flex items-center justify-center p-4 px-6 py-3 overflow-hidden font-medium transition duration-300 ease-out border-2 border-white rounded-full shadow-md text-white hover:bg-white hover:text-vermelho-asteca">
                    <span class="flex items-center">
                        <i class="fas fa-book-open mr-2 text-lg"></i>
                        <span class="font-semibold">Catálogo Digital</span>
                    </span>
                </a>
                <p class="text-white mt-2 text-sm max-w-[200px] text-center md:text-right">
                    Conheça todos os nossos produtos em nosso catálogo completo
                </p>
            </div>
        </div>

        <!-- Redes Sociais -->
        <div class="w-full md:w-1/4 flex flex-col justify-center items-center md:items-end">
            <p class="mb-4">Siga-nos:</p>
            <div class="flex space-x-4">
                <a href="https://www.tiktok.com/@askov_oficial" target="_blank" class="text-white hover:text-red-800">
                    <i class="fab fa-tiktok fa-lg"></i>
                </a>
                <div class="relative group">
                    <!-- Botão do Instagram -->
                    <button class="text-white hover:text-red-800 focus:outline-none">
                        <i class="fab fa-instagram fa-xl"></i>
                    </button>

                    <!-- Dropdown -->
                    <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-4 w-48 bg-white rounded-lg shadow-lg z-50">
                        <!-- Seta do dropdown -->
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                            <div class="border-8 border-solid border-transparent border-t-white"></div>
                        </div>

                        <!-- Links -->
                        <div class="py-2">
                            <a href="https://www.instagram.com/bebidasasteca" target="_blank"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-vermelho-asteca transition-colors duration-200 whitespace-nowrap">
                                @bebidasasteca
                            </a>
                            <a href="https://www.instagram.com/hinomoto_oficial" target="_blank"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-vermelho-asteca transition-colors duration-200 whitespace-nowrap">
                                @hinomoto_oficial
                            </a>
                            <a href="https://www.instagram.com/askov_oficial" target="_blank"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-vermelho-asteca transition-colors duration-200 whitespace-nowrap">
                                @askov_oficial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>