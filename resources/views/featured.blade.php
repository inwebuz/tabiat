@extends('layouts.app')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->short_name_text, 'bg' => $page->bg])

    <section class="content-block mt-0">
        <div class="container">
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

    @include('partials.contact_form')

@endsection
