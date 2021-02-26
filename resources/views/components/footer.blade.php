@php
    $phone = setting('contact.phone');
    $phone2 = setting('contact.phone2');
    $phone3 = setting('contact.phone3');
    $email = setting('contact.email');
    $telegram = setting('contact.telegram');
    $facebook = setting('contact.facebook');
    $instagram = setting('contact.instagram');
@endphp
<footer class="footer section-pattern-top">
    <div class="footer-top">
        <div class="container">
            <div class="row footer-top__wrap">
                <div class="col-lg-4 col-sm-6 footer-contacts">
                    <ul class="list-unstyled">
                        <li>
                            <svg width="40" height="40">
                                <use xlink:href="#phone"></use>
                            </svg>
                            <div>
                                @if ($phone)
                                    <a href="tel:{{ Helper::phone($phone) }}" class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">{{ $phone }}</a>
                                @endif
                                @if ($phone2)
                                    <a href="tel:{{ Helper::phone($phone2) }}" class="fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">{{ $phone2 }}</a>
                                @endif
                                @if ($phone3)
                                    <a href="tel:{{ Helper::phone($phone3) }}" class="fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s">{{ $phone3 }}</a>
                                @endif
                            </div>
                        </li>
                        <li>
                            <svg width="40" height="40">
                                <use xlink:href="#mail"></use>
                            </svg>
                            <a href="mailto:{{ $email }}" class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">{{ $email }}</a>
                        </li>
                        <li>
                            <svg width="40" height="40">
                                <use xlink:href="#marker"></use>
                            </svg>
                            <a href="{{ route('contacts') }}" class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">{{ $address }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6 footer-navbar">
                    <ul class="list-unstyled">
                        @php
                            $i = 0;
                        @endphp
                        @foreach($footerMenuItems as $key => $item)
                            @php
                                $i++;
                            @endphp
                            <li class="fadeInUp wow" data-wow-delay=".{{ $i }}s" data-wow-duration=".5s">
                                <a href="{{ $item->url }}">{{ $item->name }}</a>
                            </li>
                            @if($key == 0)
                                @foreach($categories as $key => $category)
                                    @php
                                        $i++;
                                    @endphp
                                    <li class="fadeInUp wow" data-wow-delay=".{{ $i }}s" data-wow-duration=".5s">
                                        <a href="{{ $category->url }}">{{ $category->name }}</a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-5 col-sm-12 footer-feedback">
                    <form class="form-feedback contact-form" method="post" action="{{ route('contacts.send') }}">
                        @csrf
                        <h5 class="fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">{{ __('main.feedback_form') }}</h5>
                        <div class="form-hide">
                            <div class="form-content">
                                <div class="form-group fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">
                                    <label for="c_name">
                                        <input type="text" id="c_name" name="name" placeholder="{{ __('main.form.your_name') }}">
                                    </label>
                                </div>
                                <div class="form-group fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">
                                    <label for="c_phone">
                                        <input type="text" id="c_phone" name="phone" placeholder="{{ __('main.phone_number') }}">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s">
                                <label for="c_maesage">
                                    <textarea name="message" id="c_maesage" cols="5" placeholder="{{ __('main.message') }}"></textarea>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 fadeInUp wow" data-wow-delay=".6s">{{ __('main.to_send') }}</button>
                        </div>
                        <div class="form-result"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row footer-bottom__wrap">
                <div class="col-lg-4 copyright-block d-flex align-items-center justify-content-center justify-content-lg-start">
                    <p class="fadeInLeft wow" data-wow-delay=".2s" data-wow-duration=".5s">{{ date('Y') }} &copy; {{ __('main.all_rights_reserved') }}. {{ setting('site.title') }}</p>
                </div>
                <div class="col-lg-4 footer-social__list">
                    <ul class="list-unstyled">
                        @if ($telegram)
                            <li class="fadeInLeft wow" data-wow-delay=".8s" data-wow-duration=".5s">
                                <a href="{{ $telegram }}">
                                    <svg width="20" height="20">
                                        <use xlink:href="#telegram"></use>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if ($facebook)
                            <li class="fadeInLeft wow" data-wow-delay=".9s" data-wow-duration=".5s">
                                <a href="{{ $facebook }}">
                                    <svg width="20" height="20">
                                        <use xlink:href="#facebook"></use>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if ($instagram)
                            <li class="fadeInLeft wow" data-wow-delay="1s" data-wow-duration=".5s">
                                <a href="{{ $instagram }}">
                                    <svg width="20" height="20">
                                        <use xlink:href="#instagram"></use>
                                    </svg>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-4 footer-logo">
                    <div class="footer-logo" >
                        <a href="https://inweb.uz" target="_blank" class="d-inline-block">
                            {{ __('main.developer') }} —
                            <img src="/img/icons/footer-logo.png" alt="">
                        </a>
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

<!-- Contact Modal -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('contacts.send') }}" class="contact-form">
                @csrf
                <input type="hidden" name="product_id" value="">

                {{-- <input type="hidden" name="category_id" value="">
                <input type="hidden" name="crop_category_id" value="">
                <input type="hidden" name="crop_id" value=""> --}}

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
                        <button class="btn btn-primary px-4" type="submit">
                            {{ __('main.form.send') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
