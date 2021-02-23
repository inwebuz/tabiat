@extends('layouts.app')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->short_name_text, 'bg' => $page->bg])

    <section class="section-block pt-4">
        <div class="container">

            @include('partials.breadcrumbs')

            @if($page->image)
                <div class="mb-4">
                    <img src="{{ $page->img }}" class="img-fluid" alt="{{ $page->name }}">
                </div>
            @endif

            <div class="text-block mb-4">
                {!! $page->body !!}
            </div>

            @if($page->images)
                @include('partials.page_gallery')
            @endif

        </div>
    </section>

@endsection
