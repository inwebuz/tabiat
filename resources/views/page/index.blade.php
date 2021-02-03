@extends('layouts.app')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->short_name_text, 'bg' => $page->bg])

    <section class="content-block">
        <div class="container">
            <div class="page-title-block mb-4">
                <div class="row">
                    <div class="col-lg-9 col-xl-10 mb-3 mb-lg-0">
                        <h2 class="box-header mb-0">{{ $page->name }}</h2>
                    </div>
                    <div class="col-lg-3 col-xl-2 d-flex justify-content-lg-end align-items-center">
                        <span class="page-views text-gray"><i class="fa fa-eye"></i>&nbsp;&nbsp;{{ $page->views }}</span>
                        <a href="{{ $page->print_url }}" target="_blank" class="btn btn-round standard-icon-btn print-icon">
                            <i class="fa fa-print"></i>
                        </a>
                    </div>
                </div>
            </div>

            @if($page->image)
                <div class="mb-4">
                    <img src="{{ $page->img }}" class="img-fluid" alt="{{ $page->name }}">
                </div>
            @endif

            <div class="text-block mb-5">
                {!! $page->body !!}
            </div>

            @if($page->images)
                @include('partials.page_gallery')
            @endif
        </div>
    </section>

    @include('partials.contact_form')

@endsection
