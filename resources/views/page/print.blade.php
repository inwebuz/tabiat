@extends('layouts.print')

@section('seo_title', $page->seo_title ?: $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    <div class="container my-4">
        <div class="page-title-block mb-4">
            <h2 class="main-header mb-0">{{ $page->name }}</h2>
        </div>

        @if($page->image)
            <div class="mb-4">
                <img src="{{ $page->img }}" class="img-fluid" alt="{{ $page->name }}">
            </div>
        @endif

        <div class="text-block mb-5">
            {!! $page->body !!}
        </div>
    </div>

    <script>
        window.print();
    </script>

@endsection
