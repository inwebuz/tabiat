@extends('layouts.app')

@section('seo_title', __('main.orders'))

@section('content')

    @include('partials.page_top', ['title' => __('main.orders'), 'bg' => ''])

    <section class="content-block">
        <div class="container">

            @if(Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
            @endif

            <div class="row">

                <div class="order-md-2 col-md-8 col-lg-9 mb-4 mb-md-0">


                    <div class="box">

                        @if(!$orders->isEmpty())
                            <h3 class="box-header">{{ __('main.orders') }}</h3>
                            <div class="table-responsive">
                                <table class="table standard-list-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('main.date') }}</th>
                                            <th>{{ __('main.status') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td class="text-nowrap">{{ $order->created_at->format('d-m-Y') }}</td>
                                                <td class="text-nowrap">{{ $order->status_title }}</td>
                                                <td class="shrink">
                                                    <a href="{{ $order->url }}" class="btn btn-primary">{{ __('main.to_show') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>
                                {{ __('main.no_orders') }}
                            </p>
                        @endif

                        {{ $orders->links() }}
                    </div>

                </div>

                <div class="order-md-1 col-md-4 col-lg-3">
                    @include('partials.sidebar_profile')
                </div>

            </div>

        </div>
    </section>

    @include('partials.contact_form')

@endsection
