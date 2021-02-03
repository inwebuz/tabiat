@extends('layouts.app')

@section('seo_title', $page->seo_title ? $page->seo_title : $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    <section class="content-block home-slider-block mt-0">
        <div class="home-slider-container">
            <div class="home-slider">
                {{-- @foreach ($slides as $slide) --}}
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
                {{-- @endforeach --}}
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

    @include('partials.contact_form')

@endsection
