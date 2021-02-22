@extends('layouts.app')

@section('seo_title', $page->seo_title ? $page->seo_title : $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    <main class="main">

        <section class="hero">
            <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                <source src="/media/video.mp4" type="video/mp4">
            </video>
            <div class="container">
                <div class="row hero-wrap">
                    <div class="col-lg-12">
                        <h1 class="fadeInUp wow" data-wow-delay="1s" data-wow-duration=".3s">Семяна и удобрения</h1>
                        <p class="hero-sub__title fadeInUp wow" data-wow-delay="1.2s" data-wow-duration=".3s">для сельского хозяйства</p>
                        <a href="#" class="down-link fadeInUp wow" data-wow-delay="1.4s" data-wow-duration=".3s">Скачать каталог</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="about">
            <div class="container">
                <div class="row about-wrap">
                    <div class="col-lg-6 about-left">
                        <img src="img/about/image.png" alt="">
                    </div>
                    <div class="col-lg-6 about-right">
                        <p class="sub-title fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s"><a href="{{ $pageAbout->url }}">{{ $pageAbout->name }}</a></p>
                        <div>
                            {!! $pageAbout->body !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="catalog bg-light">
            <div class="container">
                <p class="sub-title text-center fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">
                    <a href="{{ $pageCatalog->url }}">{{ $pageCatalog->name }}</a>
                </p>
                <h2 class="text-center fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">Наша продукция</h2>
                <div class="row catalog-wrap">
                    <div class="col-lg-12">
                        <div class="catalog-swiper">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach ($featuredProducts as $key => $product)
                                        <div class="swiper-slide fadeInRightBig wow" @if($key < 5) data-wow-delay=".{{ $key + 1 }}s" data-wow-duration=".5s" @endif>
                                            <div class="catalog-item">
                                                <div class="catalog-item__img">
                                                    <a href="{{ $product->url }}">
                                                        <img src="img/catalog/01.jpg" alt="" class="img-fluid">
                                                    </a>
                                                </div>
                                                <div class="catalog-item__body">
                                                    <h4><a href="#" class="black-link">МИРАБЕЛЛ F1</a></h4>
                                                    <p>Рекомендуется для выращивания в стеклянных и пленочных теплицах</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-button-prev">
                                <svg width="18" height="18">
                                    <use xlink:href="#swiper-arrow"></use>
                                </svg>
                            </div>
                            <div class="swiper-button-next">
                                <svg width="18" height="18">
                                    <use xlink:href="#swiper-arrow"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="news">
            <div class="container">
                <p class="sub-title text-center fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">
                    <a href="{{ route('news') }}">{{ __('main.all_news') }}</a>
                </p>
                <h2 class="text-center fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">{{ __('main.news') }}</h2>
                <div class="row news-wrap">
                    @foreach ($news as $key => $publication)
                        <div class="col-lg-4 col-sm-6 fadeInRightBig wow" data-wow-delay=".{{ $key + 2 }}s" data-wow-duration=".5s">
                            <div class="publication-one">
                                <a href="{{ $publication->url }}" class="news-item" style="background-image: url('./img/news/01.jpg')">
                                    <p class="date">{{ Helper::formatDate($publication->getModel()->created_at, true) }}</p>
                                    <h5>{{ $publication->name }}</h5>
                                    <span class="btn btn-out">{{ __('main.view_more') }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row news-wrap">
                    <div class="col-lg-12">
                        {!! $page->body !!}
                    </div>
                </div>
                <div class="row logo-items">
                    @foreach ($brands as $key => $brand)
                        <div class="col-lg-2 col-sm-4 col-6 fadeInRightBig wow" data-wow-delay=".{{ $key + 1 }}s" data-wow-duration=".5s">
                            <img src="{{ $brand->img }}" alt="{{ $brand->name }}" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </main>

    {{-- <section class="content-block home-slider-block mt-0">
        <div class="home-slider-container">
            <div class="home-slider">
                @foreach ($slides as $slide)
                    <div class="home-slide" style="background-image: url({{ $slide->img }})">
                        <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                            <source src="/media/video.mp4" type="video/mp4">
                        </video>
                        <div class="container h-100 d-flex align-items-center">
                            <div class="w-100">
                                <h2 class="home-slide-description-top">{{ $slide->description_top }}</h2>
                                <div class="home-slide-description">{{ $slide->description }}</div>
                                <div class="home-slide-button">
                                    <a href="{{ $slide->url }}"
                                        class="white-link">{{ $slide->button_text }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-pattern-border bg-pattern-border-bottom"></div>
        </div>
    </section>

    <section class="content-block home-categories-block">
        <div class="container">
            <div class="row">
                @foreach ($homeCategories as $category)
                    <div class="col-lg-6 mb-3">
                        <div class="home-category-one">
                            <a href="{{ $category->url }}" title="{{ $category->name }}">
                                <img src="{{ $category->medium_img }}" alt="{{ $category->name }}" class="img-fluid">
                                <div class="home-category-one-content">{{ $category->name }}</div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="content-block featured-product-block">
        <div class="container">
            <h2 class="main-header text-center">{{ __('main.best_from', ['from' => setting('site.title')]) }}</h2>
            <div class="featured-product-container">
                <div class="row">
                    @foreach ($featuredProducts as $product)
                        <div class="col-md-4 mb-4">
                            @include('partials.product_one', ['size' => 'medium'])
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('featured') }}" class="btn btn-lg btn-outline-secondary">
                    <strong>{{ __('main.view_all') }}</strong>
                </a>
            </div>
        </div>
    </section>

    <section class="content-block home-banners-block">
        <div class="row no-gutters">
            <div class="col-lg-6">
                <x-banner-middle type="middle_1" />
            </div>
            <div class="col-lg-6">
                <x-banner-middle type="middle_2" />
            </div>
        </div>
    </section>

    <section class="content-block brands-block">
        <div class="container">
            <div class="brands-slider-container standard-slick-dots">
                <div class="brands-slider">
                    @foreach ($brands as $brand)
                        <div class="brands-slide">
                            <img src="{{ $brand->img }}" alt="{{ $brand->name }}" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @include('partials.contact_form') --}}

@endsection
