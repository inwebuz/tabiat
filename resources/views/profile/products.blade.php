@extends('layouts.app')

@section('seo_title', __('main.products'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.products') }}</h1>
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


                <div class="box">

                    <h3 class="box-header">{{ __('main.products') }}</h3>

                    <div class="mb-3">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">{{ __('main.add') }}</a>
                    </div>

                    @if(!$products->isEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('main.title') }}</th>
                                    <th>{{ __('main.status') }}</th>
                                    <th></th>
                                </tr>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td><a href="{{ $product->url }}" target="_blank">{{ $product->name }}</a></td>
                                        <td class="text-nowrap">{{ $product->status_title }}</td>
                                        <td class="shrink">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">{{ __('main.edit') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <p>
                            {{ __('main.no_products') }}
                        </p>
                    @endif

                    {{ $products->links() }}
                </div>

            </div>

            <div class="order-md-1 col-md-4 col-lg-3">
                @include('partials.sidebar_profile')
            </div>

        </div>

    </div>

@endsection
