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
                </div>

                <div class="mt-4 mb-5">
                    <form action="{{ route('news') }}">
                        {{ __('main.period') }}
                        {!! __('main.from_to', [
                            'from' => '<input type="text" name="start" value="' . Helper::formatDate($periodStart) . '" class="w-date-input px-2 mx-1 btn btn-light btn-rounded news-start-date datepicker-here" data-date-format="dd.mm.yyyy">',
                            'to' => '<input type="text" name="end" value="' . Helper::formatDate($periodEnd) . '" class="w-date-input px-2 mx-1 btn btn-light btn-rounded news-end-date datepicker-here" data-date-format="dd.mm.yyyy">'
                            ]) !!}
                        <button type="submit" class="btn-round btn btn-light ml-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="news-list">
                    @forelse($publications as $publication)
                        <div class="news-list-one">
                            {{--<div class="news-one-img">
                                <a href="{{ $publication->url }}">
                                    <img src="{{ $publication->medium_img }}" alt="{{ $publication->short_name_text }}" class="img-fluid">
                                </a>
                            </div>--}}
                            <div class="news-one-info">
                                <span class="news-one-date">{{ Helper::formatDate($publication->getModel()->created_at) }}</span>
                                <span class="news-one-views">{{ $publication->views }}</span>
                            </div>
                            <div class="news-one-content">
                                <h4 class="news-one-title mb-2">
                                    <a href="{{ $publication->url }}">{{ $publication->name }}</a>
                                </h4>
                                <p class="news-one-description text-black">
                                    {{ $publication->description }}
                                </p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @empty
                        <p>{{ __('main.no_news') }}</p>
                    @endforelse
                </div>

                @if($links)
                    <div class="mb-5 pt-4 border-top border-top-light">
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

@section('scripts')
    <script>
        //
    </script>
@endsection
