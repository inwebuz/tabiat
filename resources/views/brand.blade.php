@extends('layouts.app')

@section('seo_title', $brand->seo_title ?: $brand->name)
@section('meta_description', $brand->meta_description)
@section('meta_keywords', $brand->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $brand->name, 'bg' => $brand->bg])

    <section class="section-block pt-4">
        <div class="container">

            @include('partials.breadcrumbs')

            @if(!$products->isEmpty())
                <div class="products-list">
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                @include('partials.product_one')
                            </div>
                        @endforeach
                    </div>
                    {!! $links !!}
                </div>

            @else
                <div class="lead">
                    {{ __('main.no_products') }}
                </div>
            @endif
        </div>
    </section>

    <section class="section-block">
        <div class="container">
            <div class="text-block">
                {!! $brand->body !!}
            </div>
        </div>
    </section>

@endsection
