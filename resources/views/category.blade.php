@extends('layouts.app')

@section('seo_title', $category->seo_title ? $category->seo_title : $category->name)
@section('meta_description', $category->meta_description)
@section('meta_keywords', $category->meta_keywords)
@section('body_class', 'category-page')

@section('content')

    @include('partials.page_top', ['bg' => '', 'title' => $category->name])

    <section class="section-block pt-4">
        <div class="container">

            @include('partials.breadcrumbs')

            <form action="{{ $category->url }}" class="category-main-form">
                <div class="row">
                    <div class="col-lg-9 order-lg-2 main-block">
                        @if(!$products->isEmpty())
                            {{-- <div class="text-lg-right mb-4">
                                <div class="dropdown">
                                    <button class="btn btn-link px-0 black-link dropdown-toggle" type="button" id="change-sort-dropdown-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{!! __('main.sort.' . $sortCurrent) !!}</button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="change-sort-dropdown-btn">
                                        @foreach($sorts as $sort)
                                            <span class="dropdown-item cursor-pointer change-sort-dropdown-item @if($sortCurrent == $sort) disabled @endif" data-value="{{ $sort }}" >{!! __('main.sort.' . $sort) !!}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="sort" value="{{ $sortCurrent }}">
                            </div> --}}
                            <div class="products-list">
                                <div class="row">
                                    @foreach($products as $product)
                                        <div class="col-xl-4 col-lg-6 col-md-4 col-6">
                                            @include('partials.product_one')
                                        </div>
                                    @endforeach
                                </div>
                                <div class="my-4">
                                    {!! $links !!}
                                </div>
                            </div>
                        @else
                            <div class="lead">
                                {{ __('main.no_products') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-3 order-lg-1 side-block mt-5 mt-lg-0 text-center text-lg-left">

                        @include('partials.subcategories_side_box')

                        @if ($banner)
                            <div class="side-box sticky-top-100">
                                @if ($banner->url)
                                    <a href="{{ $banner->url }}">
                                        <img src="{{ $banner->img }}" alt="{{ $banner->description }}" class="img-fluid">
                                    </a>
                                @else
                                    <img src="{{ $banner->img }}" alt="{{ $banner->description }}" class="img-fluid">
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </form>

            @if ($category->body)
                <div class="text-block my-4">
                    {!! $category->body !!}
                </div>
            @endif

        </div>
    </section>

@endsection

@section('scripts')
    <script>
        $(function(){
            let form = $('.category-main-form');
            $('.change-sort-dropdown-item').on('click', function(e){
                e.preventDefault();
                if ($(this).hasClass('active')) {
                    return;
                }
                $('#change-sort-dropdown-btn').text($(this).text());
                $(this).parent().find('.active').removeClass('active');
                $(this).addClass('active');
                let newValue = $(this).data('value');
                form.find('[name="sort"]').val(newValue);
                form.submit();
            });
            $('.side-box-list-switch').on('click', function(e){
                e.preventDefault();
                $(this).toggleClass('active');
                let targetIdHash = $(this).attr('href');
                let target = $(targetIdHash);
                if (target.length) {
                    target.find('.category-filter-row-visibility-changable').toggleClass('d-none');
                }
            });

        }); // ready end
    </script>
@endsection
