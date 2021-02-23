// Preloader

$(function () {

    setTimeout(function () {
        $('.preloader').fadeOut(500);
        new WOW().init();
    }, 1500);

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

    document.addEventListener('scroll', checkHeaderFixed);

    $('.selectric').selectric();

    let searchBtn = document.querySelector('.search-link');
    let searchField = document.querySelector('.search-field-block');
    let closeBtn = document.querySelector('.close-btn');

    searchBtn.addEventListener("click", function(e) {
        e.preventDefault();
        searchField.classList.add('active');
        document.getElementById('a_search').focus();
    });
    closeBtn.addEventListener('click', function() {
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
                slidesPerView: 1,
                slidesPerGroup: 1
            },
            575: {
                slidesPerView: 2,
                slidesPerGroup: 2
            },
            992: {
                slidesPerView: 3,
                slidesPerGroup: 3
            },
            1024: {
                slidesPerView: 4,
                slidesPerGroup: 4
            }
        }
    });

    /* contact form */
    $('.contact-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let sendUrl = form.attr('action');
        let sendData = form.serialize();
        let button = form.find('[type=submit]');
        console.log(form.find('.form-result'));
        let message = '';
        $.ajax({
            url: sendUrl,
            method: 'post',
            dataType: 'json',
            data: sendData,
            beforeSend: function(){
                // clear message
                form.find('.form-result').empty();
                // disabel send button
                button.addClass('disabled').prop('disabled', true).append('<i class="ml-1 fa fa-spin fa-circle-notch"></i>');
            }
        })
            .done(function(data) {
                form.find('input[type=text], input[type=email], textarea').val('');
                message = '<div class="alert alert-success">' +
                            data.message +
                            '</div>';
                form.find('.form-result').html(message);
                form.find('.form-hide').hide();
            })
            .fail(function(data) {
                console.log(data);
                if(data.status == 422) {
                    let result = data.responseJSON;
                    let messageContent = result.message + '<br>';
                    for (let i in result.errors) {
                        messageContent += '<span>' + result.errors[i] + '</span><br>';
                    }

                    message = '<div class="alert alert-danger">' +
                            messageContent +
                            '</div>';
                    form.find('.form-result').html(message);
                }
            })
            .always(function(){
                // enable button
                button.removeClass('disabled').prop('disabled', false).find('.fa').remove();
            });
    });
    $('#contact-modal').on('show.bs.modal', function (e) {
        let form = $(this).find('form');
        let button = $(e.relatedTarget);
        form.find('[name=product_id], [name=product_variant_combination], [name=category_id]').val('');
        if (button.data('product')) {
            form.find('[name=product_id]').val(button.data('product'));
            if (button.data('product-variant')) {
                form.find('[name=product_variant_combination]').val(button.data('product-variant'));
            }
        } else if (button.data('category')) {
            form.find('[name=category_id]').val(button.data('category'));
        }
    });

}); // ready end

$(window).on('load', function(){
    checkHeaderFixed();
}); // load end


function checkHeaderFixed(){
    if ($(window).scrollTop() > 75) {
        $('.header').addClass('js-header-fixed');
    } else {
        $('.header').removeClass('js-header-fixed');
    }
}
