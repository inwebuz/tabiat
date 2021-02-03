import { CountUp } from 'countup.js';

$(function () {
    "use strict";

    /* variables */
    let html = $('html');
    let body = $('body');

    let mousePageX, mousePageY, mouseClientX, mouseClientY;

    /* text to speech, speech synthesis */
    // let synth = window.speechSynthesis;
    // let utter = new SpeechSynthesisUtterance();
    // let speechLanguage = 'ru-RU1';
    // let currentActiveLangRegional = $('[name="active_language_regional"]');
    // if (currentActiveLangRegional.length) {
    //     speechLanguage = currentActiveLangRegional.val().replace('_', '-');
    // }
    // utter.lang = speechLanguage;
    // utter.volume = 0.7;

    /* set csrf token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* mousemove */
    $(document).on('mousemove', function(e){
        mousePageX = e.pageX;
        mousePageY = e.pageY;
        mouseClientX = e.clientX;
        mouseClientY = e.clientY;
    });

    /* tooltips */
    $('[data-toggle="tooltip"]').tooltip();

    /* hamburger nav */
    const appNavExpand = [].slice.call(document.querySelectorAll('.app-nav-expand'));
    const appNavBackLink = `<li class="app-nav-item">
        <a class="app-nav-link app-nav-back-link" href="javascript:;"></a>
    </li>`;
    if (appNavExpand) {
        appNavExpand.forEach(item => {
            item.querySelector('.app-nav-expand-content').insertAdjacentHTML('afterbegin', appNavBackLink);
            item.querySelector('.app-nav-link').addEventListener('click', (e) => {e.preventDefault();item.classList.add('active')});
            item.querySelector('.app-nav-back-link').addEventListener('click', (e) => {e.preventDefault();item.classList.remove('active')});
        });
    }
    const hamburger = document.getElementById('hamburger');
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            document.body.classList.toggle('app-nav-is-toggled');
            document.body.classList.toggle('menu-nav-is-toggled');
            hamburger.classList.toggle('active');
            hamburger.classList.toggle('inactive');
        });
    }
    $('.extended-menu-container-close').on('click', function(){
        document.body.classList.remove('app-nav-is-toggled');
        document.body.classList.remove('menu-nav-is-toggled');
        hamburger.classList.remove('active');
        hamburger.classList.add('inactive');
    });
    //$(hamburger).trigger('click');
    const eyeNavToggler = document.getElementById('eye-nav-toggler');
    if (eyeNavToggler) {
        eyeNavToggler.addEventListener('click', function(e) {
            console.log(e);
            e.preventDefault();
            document.body.classList.toggle('app-nav-is-toggled');
            document.body.classList.toggle('eye-nav-is-toggled');
            eyeNavToggler.classList.toggle('active');
            eyeNavToggler.classList.toggle('inactive');
        });
    }
    body.on('click', function(e){
        if(!$(e.target).closest('.app-nav-drill').length && !$(e.target).closest('.app-nav-toggle').length) {
            document.body.classList.remove('app-nav-is-toggled');
            document.body.classList.remove('eye-nav-is-toggled');
            document.body.classList.remove('menu-nav-is-toggled');
            if (hamburger) {
                hamburger.classList.add('inactive');
                hamburger.classList.remove('active');
            }
        }
    });
    /* end hamburger nav */

    /* countup */
    $('.countup').each(function(){
        let endVal = $(this).data('end-val');
        if (!endVal) {
            endVal = 1;
        }
        let countup = new CountUp($(this)[0], endVal);
        if (!countup.error) {
            countup.start();
        } else {
            console.error(countup.error);
        }
    });
    /* end countup */

    /* search */
    $('#header-search-dropdown').on('shown.bs.dropdown', function(){
        $(this).find('input[type="text"]').focus();
    });
    /* end search */

    /* bad eye form */
    let badEyeForm = $('.bad-eye-form');
    $('.btn-bad-eye').on('click', function(e){
        e.preventDefault();
        let form = $(this).closest('form');
        let group = $(this).closest('.btn-group');
        let param = $(this).data('param');
        let value = $(this).data('value');
        group.find('.btn').removeClass('active');
        $(this).addClass('active');
        form.find('[name=' + param + ']').val(value);
        setTimeout(function(){
            form.submit();
        }, 300);
    });
    $('.set-normal-version').on('click', function(e){
        e.preventDefault();
        let form = $('.bad-eye-form');
        form.find('input').val('normal');
        form.find('.btn-bad-eye').removeClass('active');
        setTimeout(function(){
            form.submit();
        }, 300);
    });
    badEyeForm.on('submit', function(e){
        e.preventDefault();
        let form = $(this);
        let sendData = form.serialize();

        // set classes
        let fontSize = form.find('[name=font_size]').val();
        let contrast = form.find('[name=contrast]').val();
        let images = form.find('[name=images]').val();

        html.removeClass('bad-eye-font_size-small bad-eye-font_size-normal bad-eye-font_size-large');
        html.removeClass('bad-eye-contrast-normal bad-eye-contrast-black_white bad-eye-contrast-white_black');
        html.removeClass('bad-eye-images-normal bad-eye-images-disabled');

        if (fontSize == 'normal' && contrast == 'normal' && images == 'normal') {
            html.removeClass('bad-eye');
        } else {
            html.addClass('bad-eye bad-eye-font_size-' + fontSize + ' bad-eye-contrast-' + contrast + ' bad-eye-images-' + images);
        }

        // save params
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: sendData,
        })
            .done(function(data){
                // console.log(data);
            })
            .fail(function(data){
                // console.log(data);
            });
    });
    // init bad-eye-form on start
    badEyeForm.submit();
    /* end bad eye form */

    /* review-form */
    $('.review-form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let btn = form.find('[type=submit]');

        $.ajax({
            method: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: form.serialize(),
            beforeSend: function () {
                btn.addClass('disabled').prop('disabled', true).append('<i class="fa fa-spin fa-circle-notch ml-2"></i>')
            }
        })
            .done(function (data) {
                form.before('<div class="alert alert-success">' + data.message + '</div>');
                // form.find('input, textarea').val('');
                form.remove();
            })
            .fail(function (data) {
                console.log(data);
            })
            .always(function (data) {
                setTimeout(function(){
                    btn.removeClass('disabled').prop('disabled', false).find('.fa').remove();
                }, 2000);

            });
    });


    /* contact form */
    $('.contact-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let sendUrl = form.attr('action');
        let sendData = form.serialize();
        let button = form.find('[type=submit]');
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
                message = `<div class="alert alert-success">
                            ${data.message}
                            </div>`;
                form.find('.form-result').html(message);
            })
            .fail(function(data) {
                console.log(data);
                if(data.status == 422) {
                    let result = data.responseJSON;
                    let messageContent = result.message + '<br>';
                    for (let i in result.errors) {
                        messageContent += '<span>' + result.errors[i] + '</span><br>';
                    }

                    message = `<div class="alert alert-danger">
                            ${messageContent}
                            </div>`;
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

    /* subscriber form */
    $('.subscriber-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let sendUrl = form.attr('action');
        let sendData = form.serialize();
        let button = form.find('[type=submit]');
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
                button.addClass('disabled').prop('disabled', true).append('<i class="ml-1 waiting-icon fa fa-spin fa-circle-o-notch"></i>');
            }
        })
            .done(function(data) {
                form.find('input[type=text], input[type=email], textarea').val('');
                message = `<div class="alert alert-success">
                            ${data.message}
                            </div>`;
                form.find('.form-result').html(message);
            })
            .fail(function(data) {
                console.log(data);
                if(data.status == 422) {
                    let result = data.responseJSON;
                    let messageContent = result.message + '<br>';
                    for (let i in result.errors) {
                        messageContent += '<span>' + result.errors[i] + '</span><br>';
                    }

                    message = `<div class="alert alert-danger">
                            ${messageContent}
                            </div>`;
                    form.find('.form-result').html(message);
                }
            })
            .always(function(){
                // enable button
                button.removeClass('disabled').prop('disabled', false).find('.waiting-icon').remove();
            });
    });


    // anchor smooth scroll
    //$(document).on('click', 'a[href^="#"]', function (e) {
    $(document).on('click', 'a.anchor[href^="#"]', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top
        }, 600);
    });

    /* theme scripts */

    // header background
    $(window).on('scroll', function(){
        checkHeaderBackground();
    });
    checkHeaderBackground();

    /* sub dropdown menu */
    $('.sub-dropdown-toggle').on('click', function(e){
        e.preventDefault();
        $(this).parent().find('.sub-dropdown-menu').toggleClass('show');
        e.stopPropagation();
    });
    body.on('click', function(e){
        if(!$(e.target).closest('.sub-dropdown').length) {
            $('.sub-dropdown-menu').removeClass('show');
        }
    });

    /* search toggle */
    let searchBlock = $('#search-block');
    $('.search-block-switch').on('click', function(e){
        e.preventDefault();
        if (searchBlock.hasClass('active')) {
            searchBlock.removeClass('active');
        } else {
            searchBlock.addClass('active');
            searchBlock.find('[name="q"]').trigger('focus');
        }
    });
    body.on('click', function(e){
        if(!$(e.target).closest('#search-block').length && !$(e.target).closest('.search-block-switch').length) {
            searchBlock.removeClass('active');
        }
    });

    /* language block toggle */
    let languageBlock = $('#language-block');
    $('.language-block-switch').on('click', function(e){
        e.preventDefault();
        languageBlock.toggleClass('active');
    });
    body.on('click', function(e){
        if(!$(e.target).closest('#language-block').length && !$(e.target).closest('.language-block-switch').length) {
            languageBlock.removeClass('active');
        }
    });


    // home-slider
    // let homeSlider = $('.home-slider');
    // let homeSliderAnimateElements = '.home-slide-animated-element';
    // homeSlider.on('init', function (event, slick){
    //     setTimeout(function(){
    //         // console.log(slick.$slider.find('.aos-animate'));
    //         slick.$slider.find('.slick-slide:not(.slick-current) .aos-animate').removeClass('aos-animate');
    //     }, 500);
    // });
    // homeSlider.on('afterChange', function (event, slick, currentSlide){
    //     $(this).find('.aos-animate').removeClass('aos-animate');
    //     let slide = $(slick.$slides[currentSlide]);
    //     slide.find(homeSliderAnimateElements).addClass('aos-animate');
    //     setTimeout(function(){
    //         $(window).trigger('resize');
    //     }, 500);
    // });
    // homeSlider.on('beforeChange', function (event, slick, currentSlide, nextSlide){
    //     let slide = $(slick.$slides[nextSlide]);
    //     slide.find(homeSliderAnimateElements).removeClass('aos-animate');
    //     $(window).trigger('resize');
    // });
    // homeSlider.length && homeSlider.slick({
    //     autoplay: true,
    //     autoplaySpeed: 6000,
    //     arrows: false,
    //     dots: true,
    //     fade: true
    //     // verticalSwiping: true
    //     // responsive: [
    //     //     {
    //     //         breakpoint: 1379,
    //     //         settings: {
    //     //             vertical: false,
    //     //             // dots: false
    //     //         }
    //     //     },
    //     // ]
    // });


    // subdivisions-slider
    let subdivisionsSlider = $('.subdivisions-slider');
    subdivisionsSlider.length && subdivisionsSlider.slick({
        autoplay: true,
        arrows: false,
        dots: true,
        vertical: true,
        // verticalSwiping: true
        responsive: [
            {
                breakpoint: 1379,
                settings: {
                    vertical: false,
                    // dots: false
                }
            },
        ]
    });

    // subdivisions-activity-slider
    let subdivisionsActivitySlider = $('.subdivisions-activity-slider');
    subdivisionsActivitySlider.length && subdivisionsActivitySlider.slick({
        autoplay: true,
        arrows: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 479,
                settings: {
                    slidesToShow: 1
                }
            },
        ]
    });

    // useful-links-slider
    let usefulLinksSlider = $('.useful-links-slider');
    if (usefulLinksSlider.length) {
        let usefulLinksSliderNextArrow = usefulLinksSlider.parent().find('.standard-slider-arrow-next');
        usefulLinksSlider.slick({
            autoplay: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            prevArrow: null,
            nextArrow: usefulLinksSliderNextArrow,
            responsive: [
                {
                    breakpoint: 1379,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 719,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                },
            ]
        });
    }

    // partners-slider
    let partnersSlider = $('.partners-slider');
    if (partnersSlider.length) {
        partnersSlider.slick({
            autoplay: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1080,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 2
                    }
                },
            ]
        });
    }

    // brands-slider
    let brandsSlider = $('.brands-slider');
    if (brandsSlider.length) {
        brandsSlider.slick({
            autoplay: true,
            slidesToShow: 6,
            slidesToScroll: 6,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1080,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 540,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
            ]
        });
    }

    // gallery slider
    let galleryFullSlider = $('.gallery-full-slider');
    let galleryPreviewsSlider = $('.gallery-previews-slider');
    if (galleryFullSlider.length && galleryPreviewsSlider.length) {
        galleryFullSlider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.gallery-previews-slider',
            autoplay: true
        });
        galleryPreviewsSlider.slick({
            slidesToShow: 6,
            slidesToScroll: 6,
            arrows: false,
            asNavFor: '.gallery-full-slider',
            dots: true,
            //centerMode: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 6
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 5
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
            ]
        });
    }

    /* category filters */
    // $('.category-filter-checkbox').on('change', function(){
    //     let currentMouseClientX = mouseClientX;
    //     let currentMouseClientY = mouseClientY;
    //     $('.category-filters-apply-btn').remove();
    //     let btn = '<button type="submit" class="btn btn-sm btn-secondary category-filters-apply-btn" style="position: fixed; left: ' + currentMouseClientX + 'px; top: ' + currentMouseClientY + 'px; z-index: 1000;"><i class="fa fa-check"></i></button>';
    //     setTimeout(() => $(this).parent().append(btn), 100);
    // });
    // body.on('click', function(e){
    //     if (!$(e.target).closest('.category-filter-row').length) {
    //         $('.category-filters-apply-btn').remove();
    //     }
    // });

    // countup
    $(window).on('scroll', function(){
        showCountupElements();
    });
    showCountupElements();

    /* aos animations */
    AOS.init();

    /* text to speech btn */
    // let textToSpeechBtn = $('.text-to-speech-btn, .text-to-speech-float-btn');
    // body.on('click', '.text-to-speech-btn, .text-to-speech-float-btn', function(e){
    //     e.preventDefault();
    //     if (synth.speaking) {
    //         synth.cancel();
    //         $('.text-to-speech-float-btn').remove();
    //         return;
    //     }
    //     let text = $(this).data('text');
    //     // let selObj = window.getSelection();
    //     // let text = selObj.toString();
    //     if (text) {
    //         utter.text = text;
    //         $(this).find('.fa').addClass('pulsate-fwd');
    //         synth.speak(utter);
    //         utter.onend = function() {
    //             // alert('Speech has finished');
    //             $('.text-to-speech-float-btn').remove();
    //         }
    //     }
    // });

    // let textToSpeechBtnTimeout;
    // document.addEventListener('selectionchange', () => {
    //     clearTimeout(textToSpeechBtnTimeout);
    //     let selObj = window.getSelection();
    //     let text = selObj.toString();
    //     $('.text-to-speech-float-btn').remove();
    //     if (synth.speaking) {
    //         synth.cancel();
    //     }
    //     if (text) {
    //         let currentMouseClientX = mouseClientX;
    //         let currentMouseClientY = mouseClientY;
    //         textToSpeechBtnTimeout = setTimeout(function(){
    //             let btn = '<button class="btn btn-primary btn-round text-to-speech-float-btn" style="position: fixed; left: ' + currentMouseClientX + 'px; top: ' + currentMouseClientY + 'px; z-index: 1000;" data-text="' + text + '"><i class="fa fa-microphone"></i></button>';
    //             $(btn).appendTo(body);
    //         }, 500);
    //     }
    // });
    /* end text to speech btn */


    // // event after text has been spoken
    // utter.onend = function() {
    //     alert('Speech has finished');
    // }
    // // speak
    // synth.speak(utter);

    let mobileMenu = $('.mobile-menu');
    mobileMenu.find('.aos-animated-element').removeClass('aos-animate');
    $('.mobile-menu-switch').on('click', function(e){
        e.preventDefault();
        if (mobileMenu.hasClass('active')) {
            mobileMenu.removeClass('active');
            mobileMenu.find('.aos-animated-element').removeClass('aos-animate');
        } else {
            mobileMenu.addClass('active');
            mobileMenu.find('.aos-animated-element').addClass('aos-animate');
        }
    });
    body.on('click', function(e){
        if (!$(e.target).closest('.mobile-menu-switch').length && !$(e.target).closest('.mobile-menu').length) {
            mobileMenu.removeClass('active');
            mobileMenu.find('.aos-animated-element').removeClass('aos-animate');
        }
    });


    /* cart */
    // add item to cart
    body.on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (btn.hasClass('disabled')) {
            return false;
        }
        let id = btn.attr('data-id');
        let variantId = btn.attr('data-variant-id');
        let name = btn.attr('data-name');
        let price = btn.attr('data-price');
        let quantity = 1;
        if (!id || !name || !price || !quantity) {
            return false;
        }
        $.ajax({
            url: '/cart',
            data: {
                id,
                variantId,
                name,
                price,
                quantity
            },
            method: 'post',
            beforeSend: function () {
                btn.addClass('disabled').prop('disabled', true).append('<i class="fa fa-circle-notch fa-spin ml-1"></i>');
            }
        })
            .done(function (data) {
                // console.log(data);

                updateCartInfo(data.cart);
                $('#cart-modal').modal('show');
            })
            .fail(function (data) {
                // console.log(data);
            })
            .always(function (data) {
                setTimeout(() => {
                    btn.removeClass('disabled').prop('disabled', false).find('i').remove();
                }, 500)
            });
    });

    // update cart item
    let updateCartTimeout;
    body.on('change', '.update-cart-quantity-input', function (e) {

        clearTimeout(updateCartTimeout);
        updateCartTimeout = setTimeout(() => {
            let input = $(this);
            let id = input.attr('data-id');
            let quantity = +input.val();

            if (!id || !quantity) {
                return false;
            }
            $.ajax({
                url: '/cart/update',
                data: {
                    id,
                    quantity
                },
                method: 'post',
                beforeSend: function () {
                    input.addClass('disabled').prop('disabled', true);
                }
            })
                .done(function (data) {
                    // console.log(data);
                    updateCartInfo(data.cart);
                    input.closest('tr').find('.product_total').text(data.lineTotalFormatted);
                })
                .fail(function (data) {
                    // console.log(data);
                })
                .always(function (data) {
                    input.removeClass('disabled').prop('disabled', false);
                });
        }, 500);
    });

    // remove cart item
    body.on('click', '.remove-from-cart-btn', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (btn.hasClass('disabled')) {
            return false;
        }
        let url = btn.attr('href');

        $.ajax({
            url: url,
            data: {
                _method: 'DELETE'
            },
            method: 'post',
            beforeSend: function () {
                btn.addClass('disabled').prop('disabled', true).empty().append('<i class="fa fa-circle-notch fa-spin ml-1"></i>');
            }
        })
            .done(function (data) {
                // console.log(data);
                btn.closest('tr').remove();
                updateCartInfo(data.cart);
            })
            .fail(function (data) {
                // console.log(data);
            })
            .always(function (data) {
                setTimeout(() => {
                    btn.removeClass('disabled').prop('disabled', false).find('i').remove();
                }, 500);
            });
    });

    function updateCartInfo(cart) {
        $('.cart_count').text(cart.quantity);
        $('.cart_total_price').text(cart.totalFormatted);
        $('.cart_total_min_price').text(cart.totalMinFormatted);
        // toggleCartTotalMessages(cart.total);
    }

    function toggleCartTotalMessages(cartTotal) {
        let checkoutBtns = $('.checkout_btn a');
        let warningMessages = $('.order-amount-too-high-warning');
        let maxTotal = warningMessages.attr('data-max') != undefined ? +warningMessages.attr('data-max') : 50000000;
        if (checkoutBtns.length && warningMessages.length) {
            if (cartTotal > maxTotal) {
                checkoutBtns.addClass('disabled');
                warningMessages.removeClass('d-none');
            } else {
                checkoutBtns.removeClass('disabled');
                warningMessages.addClass('d-none');
            }
        }
    }
    /* end cart */

    /* wishlist */
    // add item to wishlist
    body.on('click', '.add-to-wishlist-btn', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (btn.hasClass('disabled')) {
            return false;
        }
        let parentRow = btn.closest('.wishlist-tr-row');
        let url = btn.data('add-url');
        let id = btn.attr('data-id');
        let name = btn.attr('data-name');
        let price = btn.attr('data-price');
        let quantity = 1;
        let loader = 'add-spinner';
        if (btn.hasClass('only-icon')) {
            loader = 'flash-icon';
        }

        if (!id || !name || !price || !quantity) {
            return false;
        }
        $.ajax({
            url: url,
            data: {
                id,
                name,
                price,
                quantity
            },
            method: 'post',
            beforeSend: function () {
                // disable btn
                btn.addClass('disabled').prop('disabled', true);
                // add loader
                if (loader == 'flash-icon') {
                    btn.find('.fa-heart').addClass('pulse');
                } else {
                    btn.append('<i class="fa fa-circle-notch fa-spin ml-1"></i>');
                }
            }
        })
            .done(function (data) {
                updateWishlistInfo(data.wishlist);
                if (parentRow.length) {
                    parentRow.remove();
                } else {
                    btn.removeClass('add-to-wishlist-btn').addClass('remove-from-wishlist-btn').html(btn.attr('data-delete-text'));
                }
            })
            .fail(function (data) {
                console.log(data);
            })
            .always(function (data) {
                setTimeout(() => {
                    // enable btn
                    btn.removeClass('disabled').prop('disabled', false);
                    if (loader == 'flash-icon') {
                        btn.find('.fa-heart').removeClass('pulse');
                    } else {
                        btn.find('i.fa-spin').remove();
                    }
                }, 500);
            });
    });

    // remove wishlist item
    body.on('click', '.remove-from-wishlist-btn', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (btn.hasClass('disabled')) {
            return false;
        }
        let url = btn.data('remove-url');
        let loader = 'add-spinner';
        if (btn.hasClass('only-icon')) {
            loader = 'flash-icon';
        }

        $.ajax({
            url: url,
            data: {
                _method: 'DELETE'
            },
            method: 'post',
            beforeSend: function () {
                // disable btn
                btn.addClass('disabled').prop('disabled', true);
                // add loader
                if (loader == 'flash-icon') {
                    btn.find('.fa-heart').addClass('pulse');
                } else {
                    btn.append('<i class="fa fa-circle-notch fa-spin ml-1"></i>');
                }
            }
        })
            .done(function (data) {
                // console.log(data);
                btn.closest('tr').remove();
                updateWishlistInfo(data.wishlist);
                btn.removeClass('remove-from-wishlist-btn').addClass('add-to-wishlist-btn').html(btn.attr('data-add-text'));
            })
            .fail(function (data) {
                // console.log(data);
            })
            .always(function (data) {
                setTimeout(() => {
                    // enable btn
                    btn.removeClass('disabled').prop('disabled', false);
                    if (loader == 'flash-icon') {
                        btn.find('.fa-heart').removeClass('pulse');
                    } else {
                        btn.find('i.fa-spin').remove();
                    }
                }, 500);
            });
    });

    function updateWishlistInfo(wishlist) {
        $('.wishlist_count').text(wishlist.quantity);
    }
    /* end wishlist */

    $('.input-number-decrement, .input-number-increment').on('click', function(){
        let changeValue = $(this).hasClass('input-number-decrement') ? -1 : 1;
        let input = $(this).parent().find('.input-number');
        let newValue = +input.val() + changeValue;
        if (newValue < 1) {
            newValue = 1;
        }
        input.val(newValue);
        input.trigger('change');
    });

    /* end theme scripts */
});

function checkHeaderBackground() {
    let currentScrollTop = $(window).scrollTop();
    let header = $('#header');
    if (currentScrollTop > 0) {
        if (!header.hasClass('fixed')) {
            header.addClass('fixed');
        }
    } else {
        if (header.hasClass('fixed')) {
            header.removeClass('fixed');
        }
    }
}

function showCountupElements() {
    let elements = $('.countup-when-visible');
    elements.each(function(){
        let elem = $(this);
        if (!elem.hasClass('countup-processed') && isScrolledIntoView(elem)) {
            elem.addClass('countup-processed');
            let endVal = elem.data('end-val');
            if (!endVal) {
                endVal = 1;
            }
            let countup = new CountUp(elem[0], endVal);
            if (!countup.error) {
                countup.start();
            } else {
                console.error(countup.error);
            }
        }
    });
}
function isScrolledIntoView(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
