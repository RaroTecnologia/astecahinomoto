@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')
<div>
    <div class="w-full max-w-full overflow-hidden">
        <!-- Swiper para Banners Principais -->
        <div class="swiper-container banner-swiper w-full h-[70vh] relative">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                <div class="swiper-slide relative w-full h-full">
                    @if($banner->link)
                    <a href="{{ $banner->link }}" class="block w-full h-full">
                        @endif
                        <picture>
                            <source media="(min-width: 768px)"
                                srcset="{{ asset('storage/banners/' . $banner->imagem_desktop) }}">
                            <source media="(max-width: 767px)"
                                srcset="{{ asset('storage/banners/' . $banner->imagem_mobile) }}">
                            <img src="{{ asset('storage/banners/' . $banner->imagem_desktop) }}"
                                alt="Banner"
                                class="w-full h-full object-cover">
                        </picture>
                        @if($banner->link)
                    </a>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Paginação específica para banner-swiper -->
            <div class="swiper-pagination banner-swiper-pagination absolute bottom-4"></div>

            <!-- Botões de navegação -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <div class="relative overflow-hidden h-80 py-7 bg-vermelho-asteca">
        <!-- Container centralizado -->
        <div class="container mx-auto">
            <!-- Swiper para Marcas -->
            <div class="swiper-container brands-swiper h-full">
                <div class="swiper-wrapper">
                    @foreach ($marcas as $marca)
                    <div class="swiper-slide flex justify-center items-center">
                        <img src="{{ asset('storage/categorias/' . $marca->imagem) }}"
                            alt="{{ $marca->nome }}"
                            class="rounded-full w-52 h-52">
                    </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <!-- Produtos em Destaque -->
    <div class="py-12">
        <div class="container mx-auto">
            <h2 class="text-center md:text-left text-vermelho-asteca text-3xl mb-8">Nossos <span class="font-extrabold">Produtos em Destaque</span></h2>

            <!-- Bloco de Produtos -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($destaques as $destaque)
                <div>
                    @if($destaque->produto)
                    <a href="{{ route('produtos.show', [
                        'slugMarca' => $destaque->produto->marca->slug ?? 'marca-nao-encontrada',
                        'slugProduto' => $destaque->produto->slug
                    ]) }}" class="block">
                        <img
                            src="{{ $destaque->imagem ? asset('storage/destaques/' . $destaque->imagem) : asset('storage/produtos/' . $destaque->produto->imagem) }}"
                            alt="{{ $destaque->produto->nome }}"
                            class="rounded-3xl w-full h-[450px] object-cover hover:scale-105 transition-all duration-300">
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Notícias em Destaque -->
    <div class="py-12">
        <div class="container mx-auto">
            <h2 class="text-center md:text-left text-gray-500 text-3xl mb-8">Fique por dentro das <span class="font-extrabold">nossas notícias</span></h2>

            <!-- Grid de Notícias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($noticias as $noticia)
                <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <img src="{{ $noticia->imagem_url ?? asset('assets/sem_imagem.png') }}"
                        alt="{{ $noticia->titulo }}"
                        class="rounded-lg mb-4 w-full h-48 object-cover">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $noticia->titulo }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($noticia->conteudo, 100) }}</p>
                    <a href="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}"
                        class="text-blue-500 font-semibold">Veja mais</a>
                </div>
                @endforeach
            </div>

        </div>

        <!-- Botão Ver Tudo -->
        <div class="mt-8 text-center">
            <a href="{{ route('noticias.index') }}" class="inline-block px-8 py-4 bg-white border border-gray-300 text-gray-700 font-semibold rounded-full shadow-sm hover:bg-gray-50">Ver mais</a>
        </div>
    </div>
</div>


<!-- BannerReceitas -->
<div class="mt-10 py-8 mx-12 rounded-3xl relative bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset('assets/bg_receitas.jpg') }}')">
    <!-- Overlay para garantir legibilidade do texto -->
    <div class="absolute inset-0 bg-black bg-opacity-30 rounded-3xl"></div>

    <div class="container mx-auto relative z-10"> <!-- z-10 para ficar acima do overlay -->
        <!-- Título e botão -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl text-white">Receitas <span class="font-extrabold">Asteca Hinomoto</span></h2>
            <a href="{{ route('receitas.index') }}"
                class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-full hover:bg-yellow-600 transition duration-300">
                Ver tudo
            </a>
        </div>

        <!-- Grid de receitas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($receitas as $receita)
            <div class="relative bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="absolute top-4 left-4 bg-yellow-500 text-white text-xs font-semibold py-1 px-3 rounded-full">
                    @if($receita->categoria)
                    {{ $receita->categoria->nome }}
                    @else
                    Sem categoria (ID: {{ $receita->categoria_id }})
                    @endif
                </div>

                <img src="{{ $receita->imagem_url ?? asset('assets/sem_imagem.png') }}"
                    alt="{{ $receita->titulo }}"
                    class="w-full h-48 object-cover">

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $receita->nome }}</h3>
                    <p class="text-gray-600">{{ Str::limit($receita->chamada, 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

</div>
@endsection
@vite(['resources/js/slider.js'])