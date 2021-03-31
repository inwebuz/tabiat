@extends('layouts.app')

@section('seo_title', $page->seo_title ? $page->seo_title : $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    <main class="main">
        <div class="home-slider-container swiper-container">
            <div class="home-slider swiper-wrapper">
                @foreach ($slides as $slide)
                    <section class="hero section-block swiper-slide" @if($slide->image) style="background-image: url({{ $slide->img }});" @endif>
                        {{-- <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                            <source src="/media/video.mp4" type="video/mp4">
                        </video> --}}
                        <div class="dark-overlay"></div>
                        <div class="container">
                            <div class="row hero-wrap">
                                <div class="col-lg-12">
                                    <h2 class="slide-header fadeInUp wow" data-wow-delay="0.5s" data-wow-duration=".3s">{{ $slide->description_top }}</h2>
                                    <p class="hero-sub__title fadeInUp wow" data-wow-delay="0.7s" data-wow-duration=".3s">{{ $slide->description }}</p>
                                    @if ($slide->button_text && $slide->url)
                                        <a href="{{ $slide->url }}" class="down-link fadeInUp wow" data-wow-delay="0.9s" data-wow-duration=".3s">{{ $slide->button_text }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>


        <section class="about section-block full-section-block">
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

        @foreach ($homePageCatalog as $homePageCatalogKey => $homePageCatalogItem)
            @if (!$homePageCatalogItem['products']->isEmpty())
                <section class="catalog @if($homePageCatalogKey % 2 == 0) bg-light-green @endif section-block full-section-block">
                    <div class="container">
                        {{-- <p class="sub-title text-center fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">
                            <a href="{{ $pageCatalog->url }}">{{ $pageCatalog->name }}</a>
                        </p> --}}
                        <h2 class="text-center mb-5 fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">{{ $homePageCatalogItem['name'] }}</h2>
                        <div class="row catalog-wrap">
                            <div class="col-lg-12">
                                <div class="catalog-swiper">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach ($homePageCatalogItem['products'] as $key => $product)
                                                <div class="swiper-slide fadeInRightBig wow" @if($key < 5) data-wow-delay=".{{ $key + 1 }}s" data-wow-duration=".5s" @endif>
                                                    @include('partials.product_one')
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="swiper-arrows">
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
                        <div class="text-center my-2">
                            <a href="{{ $homePageCatalogItem['url'] }}" class="btn btn-primary px-4">{{ __('main.all_products') }}</a>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach

        @if (!$news->isEmpty())
            <section class="news bg-light-green section-block">
                <div class="container">
                    <p class="sub-title text-center fadeInUp wow" data-wow-delay=".1s" data-wow-duration=".5s">
                        <a href="{{ route('news') }}">{{ __('main.all_news') }}</a>
                    </p>
                    <h2 class="text-center mb-5 fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">{{ __('main.news') }}</h2>
                    <div class="row news-wrap">
                        @foreach ($news as $key => $publication)
                            <div class="col-lg-4 col-sm-6">
                                @include('partials.news_one')
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($advantages)
            <section class="section-block advantages-section-block">
                <div class="container">
                    <h2 class="text-center mb-5 fadeInUp wow" data-wow-delay=".1s" data-wow-duration=".5s" >{{ __('main.advantages') }}</h2>
                    <div class="row">
                        @foreach ($advantages as $key => $advantage)
                            <div class="col-lg-4">
                                <div class="text-center px-4 fadeInRight wow mb-4" data-wow-delay=".{{ $key + 1 }}s" data-wow-duration=".5s" >
                                    @if ($advantage->image)
                                        <div class="mb-3">
                                            <img src="{{ $advantage->img }}" alt="{{ $advantage->name }}" class="img-fluid d-block ml-auto mr-auto">
                                        </div>
                                    @endif
                                    <p>{{ $advantage->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="section-block">

            <div class="container">
                <div class="row news-wrap">
                    <div class="col-lg-12">
                        {!! $page->body !!}
                    </div>
                </div>
            </div>

            <x-partners></x-partners>

        </section>

    </main>

@endsection
