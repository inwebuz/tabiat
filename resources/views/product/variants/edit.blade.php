@extends('layouts.app')

@section('seo_title', __('main.edit_product_variant') . ' - ' . $product->name )

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.edit_product_variant') }} - {{ $product->name }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="container py-5">

        @if(!$productVariantAttributes->isEmpty())
            <!-- form start -->
            <form action="{{ route('products.variants.update', ['product' => $product->id, 'variant' => $variant->id]) }}" method="POST" enctype="multipart/form-data">

                @csrf

                @method('PUT')

                @include('product.variants.partials.form')

            </form>
        @else
            <div class="panel-body">
                <span>{{ __('main.attributes_necessary') }}</span>
                <a href="{{ route('products.attributes.edit', $product->id) }}" class="btn btn-sm btn-success">
                    <i class="voyager-categories"></i>
                    <span class="hidden-xs hidden-sm">{{ __('main.attributes') }}</span>
                </a>
            </div>
        @endif

    </div>
@endsection
