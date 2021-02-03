@extends('layouts.app')

@section('seo_title', __('main.order'))
@section('meta_description', '')
@section('meta_keywords', '')

@section('content')

    @include('partials.page_top', ['title' => __('main.view_order'), 'bg' => ''])

    <section class="content-block">
        <div class="container">

            @auth
                <div class="mb-4">
                    <a href="{{ route('profile.orders') }}" class="btn btn-primary">
                        <i class="fa fa-angle-left mr-2"></i>
                        {{ __('main.back_to_orders') }}
                    </a>
                </div>
            @endauth

            @if($order->isInstallmentOrder())
                @if($order->installmentOrder->vendor_name == 'vendoo')
                    <div class="mb-4">
                        <h4 class="medium-header">{{ __('main.installment_order') }} {{ __('main.through') }} Vendoo</h4>
                        <a href="{{ config('services.vendoo.scoring_url') . $order->installmentOrder->vendor_order_id }}">{{ __('main.place_installment_order_through') }} Vendoo</a>
                        {{--<p>{{ __('main.status') }}: {{ $order->installmentOrder->vendoo_status_title }}</p>--}}
                    </div>
                @else
                    <div class="mb-4">
                        Ошибка
                    </div>
                @endif
            @endif

            <div class="box">
                <h3 class="box-header">
                    {{ __('main.order') }} #{{ $order->id }}
                </h3>

                <div class="my-4">
                    <strong>{{ __('main.status') }}:</strong>
                    <span>{{ $order->status_title }}</span>
                </div>

                <div class="mb-4">
                    @if($order->isPending())
                        @if($order->payment_method_id == \App\Order::PAYMENT_METHOD_PAYME)

                            {{--@php
                                $checkoutUrl = 'https://checkout.paycom.uz/';
                                $checkoutParams = [
                                    'm=' .  config('paycom.merchant_id'),
                                    'ac.order_id=' . $application->id,
                                    'a=' . $application->price_tiyin,
                                ];
                                $checkoutUrl .= base64_encode(implode(';', $checkoutParams));
                            @endphp

                            <form action="{{ $checkoutUrl }}">
                                <button type="submit" class="btn btn-primary">{{ __('Pay') }}</button>
                            </form>--}}


                            <form id="form-payme" method="POST" action="https://checkout.paycom.uz/">
                                <input type="hidden" name="merchant" value="{{ config('services.paycom.merchant_id') }}">
                                <input type="hidden" name="amount" value="{{ $order->total_tiyin }}">
                                <input type="hidden" name="account[order_id]" value="{{ $order->id }}">
                                <input type="hidden" name="lang" value="{{ app()->getLocale() }}">
                                <input type="hidden" name="currency" value="860">
                                <input type="hidden" name="callback" value="{{ $order->url }}">
                                <input type="hidden" name="callback_timeout" value="15">
                                <input type="hidden" name="description" value="{{ __('main.order') . ': ' . $order->id }}">
                                <input type="hidden" name="detail" value=""/>

                                <input type="hidden" name="button" data-type="svg" value="colored">
                                <div class="row">
                                    <div class="col-sm-8 col-md-6 col-lg-4 img-container">
                                        <div id="button-container" class="button-container"></div>
                                    </div>
                                </div>
                                <button type="submit" class="button d-none">{{ __('main.pay_with', ['operator' => 'Payme']) }}</button>
                            </form>
                            <script src="https://cdn.paycom.uz/integration/js/checkout.min.js"></script>
                            <script>
                                Paycom.Button('#form-payme', '#button-container')
                            </script>
                        @endif
                    @endif
                </div>

                <div class="order_table table-responsive">
                    <table class="table products-list-table border">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-bottom-0">{{ __('main.product') }}</th>
                                <th class="border-bottom-0">{{ __('main.price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $orderItem)
                                <tr class="border-bottom">
                                    <td>
                                        {{ $orderItem->name }}
                                        <strong> × {{ $orderItem->quantity }}</strong>
                                    </td>
                                    <td class="text-nowrap"> {{ Helper::formatPrice($orderItem->total) }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="order_total">
                                <td><strong>{{ __('main.total') }}</strong></td>
                                <td class="text-nowrap"><strong>{{ Helper::formatPrice($order->total) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </section>

    @include('partials.contact_form')


@endsection
