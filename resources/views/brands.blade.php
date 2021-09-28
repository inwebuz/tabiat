@extends('layouts.app')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->short_name_text, 'bg' => $page->bg])

    <section class="section-block pt-4">
        <div class="container">

            @include('partials.breadcrumbs')

            @if(!$brands->isEmpty())
                <div class="brands-list">
                    <div class="row">
                        @foreach($brands as $brand)
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <div class="brand-one mb-3 h-100 d-flex align-items-center justify-content-center">
                                    <a href="{{ $brand->url }}" class="d-block text-center" title="{{ $brand->name }}">
                                        <img src="{{ $brand->img }}" alt="{{ $brand->name }}" class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                <div class="lead">
                    {{ __('main.no_brands') }}
                </div>
            @endif
        </div>
    </section>

@endsection
