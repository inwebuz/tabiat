@extends('layouts.app')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top')

    <div class="container">

        <div class="row">
            <div class="col-lg-8 col-xl-9 main-block">

                <div class="section-heading">
                    <h2 class="main-header">{{ $page->name }}</h2>
                    <div class="section-heading-line"></div>
                    <p class="section-heading-description">{{ $page->description }}</p>
                </div>

                <div class="publications-list">
                    @foreach($publications as $publication)
                        <div class="standard-shadow-white-box">
                            @if($page->slug == 'events')
                                @include('partials.event_list_one')
                            @else
                                @include('partials.news_list_one')
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($links)
                    <div class="mb-5">
                        {!! $links !!}
                    </div>
                @endif
            </div>
            <div class="col-lg-4 col-xl-3 side-block">

                <x-sidebar :page="$page" />

            </div>
        </div>


    </div>

@endsection
