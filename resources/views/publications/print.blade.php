@extends('layouts.print')

@section('seo_title', $publication->seo_title ?: $publication->name)
@section('meta_description', $publication->meta_description)
@section('meta_keywords', $publication->meta_keywords)

@section('content')
    <div class="container my-4">
        <div class="page-title-block mb-4">
            <h2 class="main-header mb-0">{{ $publication->name }}</h2>
        </div>

        @if($publication->image)
            <div class="mb-4">
                <img src="{{ $publication->img }}" class="img-fluid" alt="{{ $publication->name }}">
            </div>
        @endif

        <div class="text-block mb-5">
            {!! $publication->body !!}
        </div>
    </div>

    <script>
        window.print();
    </script>

@endsection
