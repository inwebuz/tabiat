@extends('layouts.app')

@section('seo_title', $publication->seo_title ?: $publication->name)
@section('meta_description', $publication->meta_description)
@section('meta_keywords', $publication->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $publication->short_name_text, 'bg' => $publication->bg])

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-xl-9 main-block">

                <div class="page-title-block mb-4">
                    <div class="row">
                        <div class="col-lg-9 col-xl-10 mb-3 mb-lg-0">
                            <h2 class="main-header mb-0">{{ $publication->name }}</h2>
                        </div>
                        <div class="col-lg-3 col-xl-2 d-flex justify-content-lg-end align-items-center">
                            <span class="page-views">{{ $publication->views }}</span>
                            <a href="{{ $publication->print_url }}" class="btn btn-light btn-round print-icon" target="_blank">
                                <i class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>

                @if($publication->image)
                    <div class="mb-4">
                        <img src="{{ $publication->img }}" class="img-fluid" alt="{{ $publication->name }}">
                    </div>
                @endif

                <div class="text-block mb-5">
                    {!! $publication->body !!}
                </div>

                @if($publication->file)
                    <div class="mb-4">
                    <a href="{{ Helper::getFileUrl($publication->file) }}" class="text-dark" download="{{ Helper::getFileOriginalName($publication->file) }}">{{ __('main.to_download') }} {{ $publication->file_name }}</a>
                    </div>
                @endif

                <div class="mb-4">
                    {!! setting('site.share_buttons_code') !!}
                </div>

            </div>
            <div class="col-lg-4 col-xl-3 side-block">

                <x-sidebar :page="$page" />

            </div>
        </div>

    </div>

@endsection
