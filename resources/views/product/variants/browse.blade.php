@extends('layouts.app')

@section('seo_title', __('main.variants'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.edit') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="container py-5">

        @if(Session::has('message') && Session::has('alert-type'))
            <div class="alert alert-{{ Session::get('alert-type') }}">
                {{ Session::get('message') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info">
                {{ __('main.back') }}
            </a>
            <a href="{{ route('products.attributes.edit', $product->id) }}" class="btn btn-sm btn-info">
                {{ __('main.attributes') }}
            </a>
            <a href="{{ route('products.variants.create', $product->id) }}" class="btn btn-sm btn-success">
                {{ __('main.add_variant') }}
            </a>
        </div>

        <div class="table-responsive">
            <table id="dataTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('main.title') }}</th>
                        <th>{{ __('main.price') }}</th>
                        <th>{{ __('main.sale_price') }}</th>
                        <th>{{ __('main.in_stock') }}</th>
                        <th>{{ __('main.variant_is_active') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $variant)
                    <tr>
                        <td>{{ $variant->name }}</td>
                        <td>{{ $variant->price }}</td>
                        <td>{{ $variant->sale_price }}</td>
                        <td>{{ $variant->in_stock ? __('main.yes') :  __('main.no') }}</td>
                        <td>{{ $variant->status ?  __('main.yes') :  __('main.no') }}</td>
                        <td class="no-sort no-click bread-actions">
                            <a href="{{ route('products.variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}" class="btn btn-sm btn-primary">
                                {{ __('main.to_edit') }}
                            </a>
                            <form class="d-inline-block" action="{{ route('products.variants.destroy', ['product' => $product->id, 'variant' => $variant->id]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('main.are_you_sure') }}?')">
                                    {{ __('main.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
