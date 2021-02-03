@extends('layouts.app')

@section('seo_title', __('main.edit'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.edit') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="container py-5">

        @if(Session::has('message'))
            <div class="alert alert-success">
                {{ Session::get('message') }}
            </div>
        @endif


        <div class="row">

            <div class="order-md-2 col-md-8 col-lg-9 mb-4 mb-md-0">

                <div class="mb-4">
                    <a href="{{ route('products.attributes.edit', $product->id) }}" class="btn btn-sm btn-info">
                        {{ __('main.attributes') }}
                    </a>
                    <a href="{{ route('products.variants', $product->id) }}" class="btn btn-sm btn-info">
                        <span class="hidden-xs hidden-sm">{{ __('main.variants') }}</span>
                    </a>
                </div>

                <div class="box mb-5">

                    <h3 class="box-header">{{ $product->name }}</h3>

                    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">

                        @csrf

                        @method('PUT')

                        @include('product.form')

                    </form>


                </div>

            </div>

            <div class="order-md-1 col-md-4 col-lg-3">
                @include('partials.sidebar_profile')
            </div>

        </div>

    </div>

@endsection
