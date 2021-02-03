@extends('layouts.app')

@section('seo_title', __('main.add'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.add') }}</h1>
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

                <div class="box mb-5">

                    <h3 class="box-header">{{ __('main.add') }}</h3>

                    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">

                        @csrf

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
