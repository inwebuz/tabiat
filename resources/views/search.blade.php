@extends('layouts.app')

@section('seo_title', __('main.nav.search'))
@section('meta_description', '')
@section('meta_keywords', '')

@section('content')

    @include('partials.page_top', ['bg' => '', 'title' => __('main.search_results')]);

    <section class="section-block pt-4">
        <div class="container">
            <form action="{{ route('search') }}" class="search-form">

                <div class="input-group input-group-lg mb-4">
                    <input type="text" name="q" class="form-control" placeholder="{{ __('main.search') }}" value="{{ $q }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            {{ __('main.search') }}
                        </button>
                    </div>
                </div>

                @if(!$searches->isEmpty())
                    <div class="products-list">
                        <div class="row">
                            @foreach($searches as $search)
                                <div class="col-xl-3 col-md-4 col-6">
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

@endsection
