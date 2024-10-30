import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', function () {
    const bannerSwiperEl = document.querySelector('.banner-swiper');
    const bannerSlides = bannerSwiperEl.querySelectorAll('.swiper-slide').length;

    const bannerSwiper = new Swiper('.banner-swiper', {
        modules: [Navigation, Pagination, Autoplay],
        loop: bannerSlides > 1,
        slidesPerView: 1,
        spaceBetween: 0,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
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
        modules: [Navigation, Pagination, Autoplay],
        loop: brandSlides > 4,
        slidesPerView: 4,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.brands-swiper .swiper-button-next',
            prevEl: '.brands-swiper .swiper-button-prev',
        },
        pagination: {
            el: '.brands-swiper .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
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
                spaceBetween: 30,
                centeredSlides: false,
            },
        },
    });
});
