<header id="header">
    <div class="header-middle">
        <div class="container">
            <nav class="navbar navbar-expand navbar-dark">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ $logo }}" alt="{{ setting('site.title') }}" class="img-fluid">
                </a>
                    @php
                        $phone = setting('contact.phone');
                        $phone2 = setting('contact.phone2');
                        $phone3 = setting('contact.phone3');
                    @endphp
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
                                                {{-- @if(!$categoryChild->hasItems()) --}}
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $subItem->url }}">{{ $subItem->name }}</a>
                                                    </li>
                                                {{-- @else
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
                                                @endif --}}
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
                        {{-- <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="nav-link cart-nav-link">
                                <img src="{{ asset('images/icons/cart.png') }}" alt="{{ __('main.cart') }}">
                                <span class="cart_count">{{ $cartQuantity > 0 ? $cartQuantity : '' }}</span>
                            </a>
                        </li> --}}
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
</div>
