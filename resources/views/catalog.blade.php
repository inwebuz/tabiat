@extends('layouts.app')

@section('seo_title', $page->seo_title ? $page->seo_title : $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->short_name_text, 'bg' => $page->bg])

    <section class="section-block pt-4">

        <div class="container">

            @include('partials.breadcrumbs')

            <div class="row">
                @foreach ($products as $key => $product)
                    <div class="col-xl-3 col-md-4 col-6">
                        @include('partials.product_one')
                    </div>
                @endforeach
            </div>

            <div class="my-4">
                {!! $links !!}
            </div>


            @if ($page->body)
                <div class="text-block my-4">
                    {!! $page->body !!}
                </div>
            @endif

        </div>

    </section>



@endsection
