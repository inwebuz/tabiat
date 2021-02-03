@extends('layouts.app')

@section('seo_title', $shop->seo_title ? $shop->seo_title : $shop->name)
@section('meta_description', $shop->meta_description)
@section('meta_keywords', $shop->meta_keywords)
@section('body_class', 'shop-page')

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ $shop->name }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="shop-info py-4">
        <div class="container">
            <div class="media">
                <div class="media-img mr-3">
                    <img src="{{ $shop->medium_img }}" alt="{{ $shop->name }}" class="img-fluid">
                </div>
                <div class="media-body">
                    @if($shop->description)
                        <div class="contact-info-block mb-2">
                            {{ $shop->description }}
                        </div>
                    @endif
                    @if($shop->phone_number)
                        <div class="contact-info-block mb-2">
                            <i class="fa fa-phone mr-2"></i>
                            <a href="tel:{{ Helper::phone($shop->phone_number) }}">{{ $shop->phone_number }}</a>
                        </div>
                    @endif
                    @if($shop->email)
                        <div class="contact-info-block mb-2">
                            <i class="fa fa-envelope mr-2"></i>
                            <a href="mailto:{{ $shop->email }}">{{ $shop->email }}</a>
                        </div>
                    @endif
                    @if($shop->address)
                        <div class="contact-info-block mb-2">
                            <i class="fa fa-map-marker ml-1 mr-2"></i>
                            {{ $shop->address }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="latest-product-area pt-5">
        <div class="container">
            @if(!$products->isEmpty())
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            @include('partials.product_one')
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center lead">
                    {{ __('main.no_products') }}
                </div>
            @endif

            {!! $links !!}

        </div>
    </section>

    @php
        $rand = 'middle_' . mt_rand(1, 3);
    @endphp
    <x-banner-middle :type="$rand" />

    @include('partials.principles')

@endsection
