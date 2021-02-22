<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row footer-top__wrap">
                <div class="col-lg-4 col-sm-6 footer-contacts">
                    <ul>
                        <li>
                            <svg width="40" height="40">
                                <use xlink:href="#phone"></use>
                            </svg>
                            <div>
                                <a href="tel:998911044242" class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">+99891 104 42 42</a>
                                <a href="tel:998974289977" class="fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">+99897 428 99 77</a>
                                <a href="tel:998974289902" class="fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s">+99897 428 99 02</a>
                            </div>
                        </li>
                        <li>
                            <svg width="40" height="40">
                                <use xlink:href="#mail"></use>
                            </svg>
                            <a href="mailto:info@gozaltabiat1.uz" class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">info@gozaltabiat1.uz</a>
                        </li>
                        <li>
                            <svg width="40" height="40">
                                <use xlink:href="#marker"></use>
                            </svg>
                            <a href="java-script:" class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">Республика Узбекистан, г. Ташкент, ул. Сайрам 174, 100170</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6 footer-navbar">
                    <ul>
                        <li class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">
                            <a href="#">Главная</a>
                        </li>
                        <li class="fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">
                            <a href="#">О компании</a>
                        </li>
                        <li class="fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s">
                            <a href="#">Удобрения</a>
                        </li>
                        <li class="fadeInUp wow" data-wow-delay=".5s" data-wow-duration=".5s">
                            <a href="#">Семяна</a>
                        </li>
                        <li class="fadeInUp wow" data-wow-delay=".6s" data-wow-duration=".5s">
                            <a href="#">Новости</a>
                        </li>
                        <li class="fadeInUp wow" data-wow-delay=".7s" data-wow-duration=".5s">
                            <a href="#">Контакты</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-5 col-sm-12 footer-feedback">
                    <form class="form-feedback">
                        <h5 class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">Форма обратной связи</h5>
                        <div class="form-content">
                            <div class="form-group fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">
                                <label for="c_name">
                                    <input type="text" id="c_name" name="c_name" placeholder="Ваше имя">
                                </label>
                            </div>
                            <div class="form-group fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">
                                <label for="c_phone">
                                    <input type="text" id="c_phone" name="c_phone" placeholder="Ваш номер">
                                </label>
                            </div>
                        </div>
                        <div class="form-group fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s">
                            <label for="c_maesage">
                                <textarea name="c_maesage" id="c_maesage" cols="5"
                                        placeholder="Сообщение"></textarea>
                            </label>
                        </div>
                        <button type="submit" class="btn bounceIn wow" data-wow-delay=".8s">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row footer-bottom__wrap">
                <div class="col-lg-4 copyright-block">
                    <p class="fadeInRightBig wow" data-wow-delay=".2s" data-wow-duration=".5s">2021 © Все права защищены. OOO «GO’ZAL TABIAT»</p>
                </div>
                <div class="col-lg-4 footer-social__list">
                    <ul>
                        <li class="fadeInLeft wow" data-wow-delay=".8s" data-wow-duration=".5s">
                            <a href="#">
                                <svg width="20" height="20">
                                    <use xlink:href="#telegram"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="fadeInLeft wow" data-wow-delay=".9s" data-wow-duration=".5s">
                            <a href="#">
                                <svg width="20" height="20">
                                    <use xlink:href="#facebook"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="fadeInLeft wow" data-wow-delay="1s" data-wow-duration=".5s">
                            <a href="#">
                                <svg width="20" height="20">
                                    <use xlink:href="#instagram"></use>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 footer-logo">
                    <div class="footer-logo fadeInLeftBig wow" data-wow-delay=".2s" data-wow-duration=".5s">
                        <p>Разработка сайта —</p>
                        <img src="img/icons/footer-logo.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="overlay"></div>

{{-- <footer id="footer">
    <div class="bg-pattern-border"></div>
    <div class="footer-top"></div>
    <div class="footer-middle text-center text-lg-left">
        <div class="container">
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"></div>
                <div class="col-lg-4"></div>

                <div class="col-xl-3 col-lg-3 mb-5 mb-lg-0">
                    <div class="pt-1">
                        <a href="{{ route('home') }}" class="d-inline-block">
                            <img src="{{ $logo }}" alt="{{ setting('site.title') }}" class="img-fluid">
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 mb-5 mb-lg-0">
                    <div class="footer-menu-one">
                        <ul class="list-unstyled">
                            @foreach($footerMenuItems as $key => $item)
                            <li class="mb-1">
                                <a href="{{ $item->url }}">{{ $item->name }}</a>
                            </li>
                                @if($key == 1)
                                    @foreach($categories as $key => $category)
                                        <li class="mb-1">
                                            <a href="{{ $category->url }}">{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 mb-5 mb-lg-0">
                    <div class="footer-menu-one">
                        <ul class="list-unstyled">
                            @php
                                $phone = setting('contact.phone');
                                $phone2 = setting('contact.phone2');
                                $phone3 = setting('contact.phone3');
                            @endphp
                            <li>
                                {{ __('main.contact_with_us') }}
                            </li>
                            <li class="footer-menu-lg-text text-nowrap">
                                <a href="tel:{{ Helper::phone($phone) }}" class="font-weight-black">{{ $phone }}</a>
                            </li>
                            <li class="normal">
                                {{ __('main.address') }}: {{ $address }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="offset-xl-1 col-xl-3 col-lg-3 mb-5 mb-lg-0">
                    <div class="footer-menu-one">
                        <ul class="list-unstyled">
                            @php
                                $email = setting('contact.email');
                            @endphp
                            <li>
                                {{ __('main.our_mail') }}
                            </li>
                            <li class="text-nowrap">
                                <a href="mailto:{{ $email }}" class="font-weight-black">{{ $email }}</a>
                            </li>
                            <li class="normal">
                                {!! __('main.any_questions_and_offers') !!}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="row align-items-center">
                    <div class="col-lg-3 order-lg-3 text-center text-lg-right mb-3 mb-lg-0">
                        <a href="https://inweb.uz" target="_blank" class="developer text-light-gray">{{ __('main.developer') }} - <img src="/images/devlogo-light.png" alt="Inweb.uz" style="width: 14px;vertical-align: -2px;"></a>
                    </div>
                    <div class="col-lg-3 order-lg-2 text-center mb-4 mb-lg-0">
                        <div class="footer-social">
                            @include('partials.social_list')
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1 text-center text-lg-left">
                        <div class="copyright text-light-gray">
                            &copy; {{ date('Y') }} {{ setting('site.title') }}. {{ __('main.all_rights_reserved') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Contact Modal -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('contacts.send') }}" class="contact-form">
                @csrf
                <input type="hidden" name="product_id" value="">

                <input type="hidden" name="category_id" value="">
                <input type="hidden" name="crop_category_id" value="">
                <input type="hidden" name="crop_id" value="">

                <div class="modal-body">
                    <h5 class="modal-title" id="contact-modal-label">
                        {{ __('main.form.send_request') }}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-times"></i></span>
                        </button>
                    </h5>
                    <br>
                    <div class="form-result"></div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="{{ __('main.form.your_name') }}" required />
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="phone" placeholder="{{ __('main.form.phone') }}" required />
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="message" id="message" rows="4" placeholder="{{ __('main.form.message') }}" required ></textarea>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary" type="submit">
                            {{ __('main.form.send') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cart-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="cart-message mb-3 mt-2 text-center h4">
                    {{ __('main.product_added_to_cart') }}
                </h4>
                <div class="text-center">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">
                        {{ __('main.continue_shopping') }}
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-primary mb-2">
                        {{ __('main.go_to_cart') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
