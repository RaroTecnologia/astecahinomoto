import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', function () {
    const bannerSwiperEl = document.querySelector('.banner-swiper');
    const bannerSlides = bannerSwiperEl.querySelectorAll('.swiper-slide').length;

    const bannerSwiper = new Swiper('.banner-swiper', {
        modules: [Navigation, Pagination],
        loop: bannerSlides > 1, // Habilita o loop apenas se houver mais de 1 slide
        slidesPerView: 1, // Garante que apenas um slide seja exibido por vez
        spaceBetween: 0,
        navigation: {
            nextEl: '.banner-swiper .swiper-button-next',
            prevEl: '.banner-swiper .swiper-button-prev',
        },
        pagination: {
            el: '.banner-swiper .swiper-pagination',
            clickable: true,
        },
    });

    const brandSwiperEl = document.querySelector('.brands-swiper');
    const brandSlides = brandSwiperEl.querySelectorAll('.swiper-slide').length;

    const brandSwiper = new Swiper('.brands-swiper', {
        modules: [Navigation, Pagination],
        loop: brandSlides > 4, // Habilita o loop apenas se houver mais de 4 slides
        slidesPerView: 4, // Exibindo 4 marcas ao mesmo tempo
        spaceBetween: 30,
        navigation: {
            nextEl: '.brands-swiper .swiper-button-next',
            prevEl: '.brands-swiper .swiper-button-prev',
        },
        pagination: {
            el: '.brands-swiper .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        },
    });
});
