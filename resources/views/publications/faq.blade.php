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
                        <div class="standard-shadow-white-box mb-3 p-0">
                            <h4 class="news-one-title mb-0 cursor-pointer text-with-icon-right collapsed py-4 pl-4 pr-5" data-toggle="collapse" data-target="#publication-one-collapse-{{ $publication->id }}" >
                                {{ $publication->name }}
                                <i class="fa fa-angle-down px-3 py-4"></i>
                            </h4>
                            <div class="news-one-description text-black collapse-block-content collapse" id="publication-one-collapse-{{ $publication->id }}">
                                <div class="pb-4 px-4">
                                    {{ $publication->description }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-xl-3 side-block">

                <x-sidebar :page="$page" />

            </div>
        </div>


    </div>

@endsection
