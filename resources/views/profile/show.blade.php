@extends('layouts.app')

@section('seo_title', __('main.profile'))

@section('content')

    @include('partials.page_top', ['title' => __('main.profile_details'), 'bg' => ''])

    <section class="content-block">
        <div class="container">
            <div class="row">

                <div class="order-md-2 col-md-8 col-lg-9 mb-4 mb-md-0">

                    <div class="box">
                        <h3 class="box-header">{{ __('main.information') }}</h3>
                        <div class="mb-2">
                            <strong>{{ __('main.first_name') }}:</strong>
                            <span>{{ $user->name }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>{{ __('main.phone_number') }}:</strong>
                            <span>{{ $user->phone_number }}</span>
                        </div>
                        @if($user->address)
                            <div class="mb-1">
                                <strong>{{ __('main.address') }}:</strong>
                                <span>{{ $user->address }}</span>
                            </div>
                        @endif

                        <br>

                        <div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-lg btn-primary">{{ __('main.to_edit') }}</a>
                        </div>

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
