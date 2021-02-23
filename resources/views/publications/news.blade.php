@extends('layouts.app')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->name])

    <section class="section-block pt-4">
        <div class="container">

            @include('partials.breadcrumbs')

            <div class="row news-wrap">
                @foreach ($publications as $key => $publication)
                    <div class="col-lg-4 col-sm-6">
                        @include('partials.news_one')
                    </div>
                @endforeach
            </div>

            @if($links)
                <div class="mb-5 pt-4 border-top border-top-light">
                    {!! $links !!}
                </div>
            @endif
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        //
    </script>
@endsection
