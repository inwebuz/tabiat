@extends('layouts.app')

@section('seo_title', __('main.cart'))
@section('meta_description', '')
@section('meta_keywords', '')

@section('content')

    @include('partials.page_top', ['title' => __('main.cart'), 'bg' => ''])

    <section class="content-block">
        <div class="container">

            @if(!$cart->isEmpty())
                <div class="table-responsive">
                    <table class="table standard-list-table">
                        <thead>
                            <tr>
                                <th>{{ __('main.product') }}</th>
                                <th>{{ __('main.price') }}</th>
                                <th>{{ __('main.quantity') }}</th>
                                <th>{{ __('main.total') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $cartItem)
                                <tr>
                                    <td>
                                        <a class="d-block black-link" href="{{ $cartItem->associatedModel->url }}">
                                            <div class="media align-items-center">
                                                <div class="mr-3 d-none d-lg-block">
                                                    <img src="{{ $cartItem->associatedModel->micro_img }}" alt="{{ $cartItem->name }}">
                                                </div>
                                                <div class="media-body">
                                                    <span>{{ $cartItem->name }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <strong class="text-nowrap">{{ Helper::formatPrice($cartItem->price) }}</strong>
                                    </td>
                                    <td>
                                        <div class="cart-item-change">
                                            <span class="input-number-decrement">-</span>
                                            <input class="input-number form-control update-cart-quantity-input" type="text" value="{{ $cartItem->quantity }}" min="1" max="100"  name="cart-quantity-{{ $cartItem->id }}" data-id="{{ $cartItem->id }}">
                                            <span class="input-number-increment">+</span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-nowrap product_total">{{ Helper::formatPrice($cartItem->quantity * $cartItem->price) }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('cart.delete', $cartItem->id) }}" class="remove-from-cart-btn">
                                            <i class="fa fa-times text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-lg-6 offset-lg-6">
                        <div class="mb-4 text-center text-lg-right lead">
                            <strong>{{ __('main.total') }}: <span class="cart_total_price text-primary">{{ Helper::formatPrice($cart->getTotal()) }}</span></strong>
                        </div>
                        <div class="checkout_btn mb-4 text-center text-lg-right">
                            <a href="{{ route('cart.checkout') }}" class="btn btn-lg btn-outline-secondary">{{ __('main.proceed_to_checkout') }}</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="my-5 lead text-center">{{ __('main.cart_is_empty') }}</div>
            @endif
        </div>
    </section>

    @include('partials.contact_form')

@endsection
