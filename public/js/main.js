// Preloader

setTimeout(function () {
    $('.preloader').fadeOut(500);
    new WOW().init();
}, 1500);

$(document).ready(function () {

    // wow js

    new WOW().init();


    // Hamburger

    function hamburgerClose() {
        $('.burger').removeClass('js-open');
        $('html, body').removeClass('js-overflow');
        $('.navbar-content').removeClass('js-nav-active');
        $('.burger-menu').fadeOut(250).removeClass('js-open');
        $('.overlay').removeClass('active');
    }

    $('.burger').on('click', function(event) {
        $(this).toggleClass('js-open');
        $('html, body').toggleClass('js-overflow');
        $('.navbar-content').toggleClass('js-nav-active');
        $('.burger-menu').fadeIn(500).toggleClass('js-open').css('display', 'flex');
        $('.overlay').toggleClass('active');

        $('.close-burger').click(function(event) {
            event.preventDefault();
            hamburgerClose();
        });

        if ($(this).hasClass('js-open')) {

            document.onkeydown = function(e) {
                if (e.keyCode == 27) {
                    hamburgerClose();

                    return false;
                }
            };

        } else {
            hamburgerClose()
        }
    });


    window.addEventListener('scroll', () => {

        if ($(window).scrollTop() > 75) {
            $('.header').addClass('js-header-fixed');
        } else {
            $('.header').removeClass('js-header-fixed');
        }
    });

    $(function () {
        $('.selectric').selectric();
    });

    let searchBtn = document.querySelector('.search-link');
    let searchField = document.querySelector('.search-field-block');
    let closeBtn = document.querySelector('.close-btn');

    searchBtn.addEventListener("click", (e) => {
        e.preventDefault();
        searchField.classList.add('active');
        document.getElementById('a_search').focus();
    });
    closeBtn.addEventListener('click', () => {
        searchField.classList.remove('active');
    });


    // Swipers

    let catagolSwiper = new Swiper('.catalog-swiper .swiper-container', {
        spaceBetween: 30,
        navigation: {
            nextEl: '.catalog-swiper .swiper-button-next',
            prevEl: '.catalog-swiper .swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1
            },
            575: {
                slidesPerView: 2
            },
            992: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4
            }
        }
    });

});
