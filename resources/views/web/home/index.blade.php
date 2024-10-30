@extends('layouts.web')

@section('content')
<!-- Banners Slider -->
<div class="relative">
    <!-- Slider principal -->
    <div class="swiper banner-principal">
        <div class="swiper-wrapper">
            @foreach($banners as $banner)
            <div class="swiper-slide">
                <div class="relative">
                    <img src="{{ asset('storage/banners/' . $banner->imagem) }}"
                        alt="{{ $banner->titulo }}"
                        class="w-full h-[480px] object-cover">

                    @if($banner->titulo || $banner->subtitulo)
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <div class="text-center text-white">
                            @if($banner->titulo)
                            <h2 class="text-4xl font-bold mb-2">{{ $banner->titulo }}</h2>
                            @endif

                            @if($banner->subtitulo)
                            <p class="text-xl">{{ $banner->subtitulo }}</p>
                            @endif

                            @if($banner->link)
                            <a href="{{ $banner->link }}" class="inline-block mt-4 px-6 py-2 bg-primary text-white rounded-full hover:bg-primary-dark transition">
                                Saiba mais
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Navegação -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <!-- Paginação -->
        <div class="swiper-pagination"></div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.banner-principal', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>
@endpush

<!-- Resto do conteúdo da home -->
@endsection