<footer id="footer">
    <div class="footer-top"></div>
    <div class="footer-middle text-center text-md-left">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mb-3 mb-lg-0">
                    <div class="row">
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="footer-menu-one">
                                <h6>{{ __('main.site_map') }}</h6>
                                <ul class="list-unstyled">
                                    @foreach($footerMenuItems as $item)
                                        <li>
                                            <a href="{{ $item->url }}">{{ $item->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="footer-menu-one">
                                <h6>{{ __('main.information') }}</h6>
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="{{ route('news') }}">{{ __('main.news') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('vacancies') }}">{{ __('main.vacancies') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="footer-menu-one">
                                <h6>{{ __('main.our_contacts') }}</h6>
                                <ul class="list-unstyled">
                                    <li>
                                        {{ __('main.production') }}:
                                        {{ $address }}
                                    </li>
                                    <li>
                                        {{ __('main.office') }}:
                                        {{ $address }}
                                    </li>
                                    <li class="with-icon">
                                        <i class="fa fa-phone"></i>
                                        {{ __('main.phone') }}:
                                        @php
                                            $phone = setting('contact.phone');
                                            $phone2 = setting('contact.phone2');
                                        @endphp
                                        @if($phone)
                                            <a href="tel:{{ Helper::phone($phone) }}" class="text-primary">{{ $phone }}</a>
                                        @endif
                                        @if($phone2)
                                            <a href="tel:{{ Helper::phone($phone2) }}" class="text-primary">{{ $phone2 }}</a>
                                        @endif
                                    </li>
                                    <li class="with-icon">
                                        <i class="fa fa-envelope-o"></i>
                                        {{ __('main.email') }}:
                                        @php
                                            $email = setting('contact.email');
                                        @endphp
                                        @if($email)
                                            <a href="mailto:{{ $email }}" class="text-primary">{{ $email }}</a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="footer-icons-block">
                        <div class="footer-icon-row">
                            <a href="{{ route('contacts') }}" class="footer-icon">
                                <img src="{{ asset('img/icons/phone.png') }}" alt="Phone">
                            </a>
                        </div>
                        <div class="footer-icon-row">
                            <a href="{{ route('appeals') }}" class="footer-icon" data-toggle="tooltip" data-placement="left" title="{{ __('main.feedback_title') }}">
                                <img src="{{ asset('img/icons/mail.png') }}" alt="Feedback">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 order-lg-2 text-center text-lg-right mb-3 mb-lg-0">
                    <div class="footer-social">
                        @include('partials.social_list')
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1 text-center text-lg-left mb-3 mb-lg-0">
                    <div class="copyright text-white">
                        &copy; {{ date('Y') }} {{ setting('site.title') }}. {{ __('main.all_rights_reserved') }}
                    </div>
                </div>
                <div class="col-lg-3 order-lg-3 text-center text-lg-right">
                    <a href="https://inweb.uz" target="_blank" class="developer text-light font-weight-light">{{ __('main.developer') }} - <img src="/img/devlogo-light.png" alt="Inweb.uz" style="width: 14px;vertical-align: -2px;"></a>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="footer-copyright">&copy; {{ date('Y') }} {{ setting('site.title') }} | {{ __('main.all_rights_reserved') }}</div>
    @if(setting('contact.facebook'))
        <li><a href="{{ setting('contact.facebook') }}"><i class="fa fa-facebook-f"></i></a></li>
    @endif
    @if(setting('contact.instagram'))
        <li><a href="{{ setting('contact.instagram') }}"><i class="fa fa-instagram"></i></a></li>
    @endif
    @if(setting('contact.telegram'))
        <li><a href="{{ setting('contact.telegram') }}"><i class="fa fa-paper-plane"></i></a></li>
    @endif--}}
</footer>
