@extends('layouts.app')

@section('seo_title', __('main.become_a_seller_application'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.become_a_seller_application') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="container py-5">

        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="row">

            <div class="order-md-2 col-md-8 col-lg-9 mb-4 mb-md-0">

                <div class="box">
                    <h3 class="box-header">{{ __('main.application') }}</h3>
                    @if ($userApplication)
                        <div class="mb-2">
                            <strong>ID:</strong>
                            <span>{{ $userApplication->id }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>{{ __('main.status') }}:</strong>
                            <span>{{ $userApplication->status_title }}</span>
                        </div>
                    @else
                        <div class="mb-2">
                            {{ $errorText }}
                        </div>
                    @endif
                        <br>

                </div>
            </div>

            <div class="order-md-1 col-md-4 col-lg-3">
                @include('partials.sidebar_profile')
            </div>

        </div>
    </div>

@endsection
