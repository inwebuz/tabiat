@extends('layouts.app')

@section('seo_title', __('main.nav.search'))
@section('meta_description', '')
@section('meta_keywords', '')

@section('content')

    @include('partials.page_top', ['bg' => '', 'title' => __('main.search_results')]);

    <section class="content-block mt-0">
        <div class="container">
            <form action="{{ route('search') }}" class="search-form">

                <div class="input-group input-group-lg mb-4">
                    <input type="text" name="q" class="form-control" placeholder="{{ __('main.search') }}" value="{{ $q }}">
                    <div class="input-group-append">
                        <button class="btn btn-light" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                @if(!$searches->isEmpty())
                    <div class="products-list">
                        <div class="row">
                            @foreach($searches as $search)
                                <div class="col-xl-3 col-lg-3 col-md-6">
                                    @include('partials.product_one', ['product' => $search->searchable])
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
            </form>
        </div>
    </section>

    @include('partials.contact_form')

@endsection
