@extends('layouts.app')

@section('seo_title', $publication->seo_title ?: $publication->name)
@section('meta_description', $publication->meta_description)
@section('meta_keywords', $publication->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $publication->name, 'bg' => $publication->bg])

    <section class="section-block">
        <div class="container">

            @include('partials.breadcrumbs')

            <div class="text-block mb-5">
                @if($publication->image)
                    <img src="{{ $publication->img }}" class="img-fluid float-left pr-3 pb-3" alt="{{ $publication->name }}">
                @endif

                {!! $publication->body !!}
            </div>

            @if($publication->file && $publication->file != '[]')
                <div class="mb-4">
                <a href="{{ Helper::getFileUrl($publication->file) }}" class="text-dark" download="{{ Helper::getFileOriginalName($publication->file) }}">{{ __('main.to_download') }} {{ $publication->file_name }}</a>
                </div>
            @endif

            <div class="mb-4">
                {!! setting('site.share_buttons_code') !!}
            </div>

        </div>
    </section>

@endsection
