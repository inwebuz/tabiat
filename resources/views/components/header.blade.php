<nav class="burger-menu">
    <div class="burger-menu__wrap">
        <ul class="burger-menu__list list-unstyled">
            @foreach($headerMenuItems as $key => $item)
                <li>
                    <a href="{{ $item->url }}">{{ $item->name }}</a>
                </li>
                @if($key == 0)
                    @foreach($categories as $key => $category)
                        <li>
                            <a href="{{ $category->url }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
        @php
            $phone = setting('contact.phone');
            $phone2 = setting('contact.phone2');
            $phone3 = setting('contact.phone3');
            $phone4 = setting('contact.phone4');
        @endphp
        @if ($phone)
            <a href="tel:{{ Helper::phone($phone) }}" class="call-link">{{ $phone }}</a>
        @endif
        @if ($phone2)
            <a href="tel:{{ Helper::phone($phone2) }}" class="call-link">{{ $phone2 }}</a>
        @endif
        @if ($phone3)
            <a href="tel:{{ Helper::phone($phone3) }}" class="call-link">{{ $phone3 }}</a>
        @endif
        @if ($phone4)
            <a href="tel:{{ Helper::phone($phone4) }}" class="call-link">{{ $phone4 }}</a>
        @endif
    </div>
</nav>

<header class="header header-static js-header-fixed">
    <div class="container">
        <div class="header-wrap">
            <div class="logo"><a href="{{ route('home') }}"><img src="{{ $logo }}" alt=""></a></div>
            <nav class="navbar">
                <div class="navbar-content">
                    <ul class="nav-list list-unstyled">
                        @foreach($headerMenuItems as $key => $item)
                            <li class="nav-item">
                                <a href="{{ $item->url }}" class="nav-link hover-line">{{ $item->name }}</a>
                            </li>
                            @if($key == 0)
                                @foreach($categories as $key => $category)
                                    <li class="nav-item">
                                        <a href="{{ $category->url }}" class="nav-link hover-line">{{ $category->name }}</a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                        @if ($phone)
                            <li class="nav-item dropdown">
                                <a href="tel:{{ Helper::phone($phone) }}" class="nav-link hover-line dropdown-toggle" data-toggle="dropdown" >{{ $phone }}</a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a href="tel:{{ Helper::phone($phone) }}" class="dropdown-item">{{ $phone }}</a>
                                    @if ($phone2)
                                        <a href="tel:{{ Helper::phone($phone2) }}" class="dropdown-item">{{ $phone2 }}</a>
                                    @endif
                                    @if ($phone3)
                                        <a href="tel:{{ Helper::phone($phone3) }}" class="dropdown-item">{{ $phone3 }}</a>
                                    @endif
                                    @if ($phone4)
                                        <a href="tel:{{ Helper::phone($phone4) }}" class="dropdown-item">{{ $phone4 }}</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                    </ul>

                </div>
                <div class="header-right__content">
                    <a href="java-script:" class="search-link">
                        <svg width="18" height="18">
                            <use xlink:href="#search"></use>
                        </svg>
                    </a>
                    <form class="search-field-block" id="header-search-form" action="{{ route('search') }}">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <button class="btn btn-link" type="submit">
                                    <svg width="15" height="15">
                                        <use xlink:href="#search"></use>
                                    </svg>
                                </button>
                            </span>
                            <input type="text" id="a_search" class="form-control px-0" minlength="3" required name="q">
                            <span class="input-group-append">
                                <span class="btn btn-link close-btn">
                                    <svg width="12" height="12">
                                        <use xlink:href="#close"></use>
                                    </svg>
                                </span>
                            </span>
                        </div>
                    </form>

                    <div id="language-block" class="dropdown mr-xl-4 mr-3">
                        <a href="{{ $switcher->getActive()->url }}" class="text-uppercase dropdown-toggle white-link" data-toggle="dropdown">
                            {{  __('main.language_key_' . $switcher->getActive()->key) }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($switcher->getValues() as $item)
                                <li>
                                    <a href="{{ $item->url }}" class="dropdown-item text-uppercase">{{ __('main.language_key_' . $item->key) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="burger">
                        <span></span><span></span><span></span><span></span>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

{{-- <header id="header">
    <div class="header-middle">
        <div class="container">
            <nav class="navbar navbar-expand navbar-dark">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ $logo }}" alt="{{ setting('site.title') }}" class="img-fluid">
                </a>

                    <div class="collapse navbar-collapse" id="header-middle-navbar">
                        <ul class="navbar-nav d-none d-lg-flex">
                            @foreach($headerMenuItems as $key => $item)
                                @if(!$item->hasItems())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ $item->url }}">{{ $item->name }}</a>
                                    </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="{{ $category->url }}" data-toggle="dropdown">{{ $category->name }}</a>
                                        <ul class="dropdown-menu">
                                            @foreach($item->getItems as $subItem)
                                                @if(!$categoryChild->hasItems())
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $subItem->url }}">{{ $subItem->name }}</a>
                                                    </li>
                                                @else
                                                    <li class="sub-dropdown">
                                                        <a class="dropdown-item sub-dropdown-toggle" href="{{ $item->url }}">{{ $item->name }} <i class="fa fa-angle-down"></i></a>
                                                        <ul class="dropdown-menu sub-dropdown-menu">
                                                            @foreach($item->getItems() as $subitem)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ $subitem->url }}">- {{ $subitem->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                                @if($key == 0)
                                    @foreach($categories as $key => $category)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ $category->url }}">{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="tel:{{ Helper::phone($phone) }}" data-toggle="dropdown">{{ $phone }}</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="tel:{{ Helper::phone($phone) }}">{{ $phone }}</a>
                                </li>
                                @if ($phone2)
                                    <li>
                                        <a class="dropdown-item" href="tel:{{ Helper::phone($phone2) }}">{{ $phone2 }}</a>
                                    </li>
                                @endif
                                @if ($phone3)
                                    <li>
                                        <a class="dropdown-item" href="tel:{{ Helper::phone($phone3) }}">{{ $phone3 }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#search-block" class="nav-link search-block-switch">
                                <img src="{{ asset('images/icons/search.png') }}" alt="{{ __('main.search') }}">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="nav-link cart-nav-link">
                                <img src="{{ asset('images/icons/cart.png') }}" alt="{{ __('main.cart') }}">
                                <span class="cart_count">{{ $cartQuantity > 0 ? $cartQuantity : '' }}</span>
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <span class="nav-link text-uppercase language-block-switch cursor-pointer">
                                {{ $switcher->getActive()->key }}
                                <input type="hidden" name="active_language_regional" value="{{ $activeLanguageRegional }}">
                            </span>
                            <div id="language-block" class="language-block">
                                @foreach ($switcher->getValues() as $item)
                                    @if ($item->key == $switcher->getActive()->key)
                                        @continue
                                    @endif
                                    <a href="{{ $item->url }}" class="text-uppercase black-link">{{ $item->key }}</a>
                                @endforeach
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#mobile-menu" class="nav-link mobile-menu-switch">
                                <img src="{{ asset('images/icons/menu.png') }}" alt="{{ __('main.menu') }}">
                            </a>
                        </li>
                    </ul>

            </nav>
        </div>
    </div>
</header>

<div id="search-block" class="search-block">
    <form action="{{ route('search') }}" class="search-form">
        <div class="input-group input-group-lg">
            <input type="text" name="q" class="form-control" placeholder="{{ __('main.search') }}">
            <div class="input-group-append">
                <button class="btn btn-light" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<div class="mobile-menu">
    <div class="mobile-menu-container">
        <ul class="mobile-menu-list">
            @foreach($headerMenuItems as $key => $menuItem)
                <li class="aos-animated-element" data-aos="fade-up" data-aos-delay="{{ $key * 100 + 200 }}">
                    <a href="{{ $menuItem->url }}">{{ $menuItem->name }}</a>
                </li>
            @endforeach
            <li class="aos-animated-element" data-aos="fade-up" data-aos-delay="{{ ($key + 1) * 100 + 200 }}">
                <a href="{{ route('cart.index') }}">{{ __('main.cart') }}</a>
            </li>
            <li class="aos-animated-element" data-aos="fade-up" data-aos-delay="{{ ($key + 2) * 100 + 200 }}">
                <a href="{{ route('wishlist.index') }}">{{ __('main.wishlist') }}</a>
            </li>
        </ul>
    </div>
</div> --}}
