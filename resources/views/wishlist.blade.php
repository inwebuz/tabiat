@extends('layouts.app')

@section('seo_title', __('main.wishlist'))
@section('meta_description', '')
@section('meta_keywords', '')

@section('content')

    @include('partials.page_top', ['title' => __('main.wishlist'), 'bg' => ''])

    <section class="content-block">
        <div class="container">
            @if(!$wishlist->isEmpty())
                <div class="table-responsive">
                    <table class="table standard-list-table">

                        <thead>
                            <tr>
                                <th>{{ __('main.product') }}</th>
                                <th>{{ __('main.price') }}</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($wishlistItems as $wishlistItem)
                                <tr class="wishlist-tr-row">
                                    <td>
                                        <a class="d-block black-link" href="{{ $wishlistItem->associatedModel->url }}">
                                            <div class="media align-items-center">
                                                <div class="d-none d-lg-block mr-3">
                                                    <img src="{{ $wishlistItem->associatedModel->micro_img }}" alt="{{ $wishlistItem->name }}">
                                                </div>
                                                <div class="media-body">
                                                    <span>{{ $wishlistItem->name }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <strong class="text-nowrap">{{ Helper::formatPrice($wishlistItem->price) }}</strong>
                                    </td>
                                    <td>
                                        <button data-remove-url="{{ route('wishlist.delete', $wishlistItem->id) }}"
                                            class="remove-from-wishlist-btn wishlist-control-btn btn btn-link px-0"
                                        >
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="my-5 lead text-center">{{ __('main.wishlist_is_empty') }}</div>
            @endif
        </div>
    </section>

    @include('partials.contact_form')

@endsection
