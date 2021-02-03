@extends('layouts.app')

@section('seo_title', __('main.profile_edit'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.shop_edit') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="container py-5">

        @if(Session::has('message'))
            <div class="alert alert-success">
                {{ Session::get('message') }}
            </div>
        @endif


        <div class="row">

            <div class="order-md-2 col-md-8 col-lg-9 mb-4 mb-md-0">

                <form action="{{ route('profile.shop.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="box mb-5">
                        <h3 class="box-header">{{ __('main.information') }}</h3>
                        <div class="row">
                            <div class="col-xl-7 col-lg-9">

                                <div class="form-group">
                                    <label for="form_name">{{ __('main.form.name') }} <span
                                            class="text-danger">*</span></label>
                                    <input id="form_name" type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name') ?? $shop->name }}" required
                                           autofocus>
                                    @error('name')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="form_image">{{ __('main.logo') }} <span
                                            class="text-danger">*</span></label>
                                    @if($shop->image)
                                        <div class="mb-3">
                                            <img src="{{ $shop->medium_img }}" alt="" class="img-fluid">
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <input id="form_image" type="file"
                                               class="@error('image') is-invalid @enderror"
                                               name="image"
                                        >
                                    </div>
                                    @error('image')
                                    <div class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="form_description">{{ __('main.description') }}</label>
                                    <textarea id="form_description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              name="description"
                                    >{{ old('description') ?? $shop->description }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box mb-5">
                        <h3 class="box-header">{{ __('main.contact_information') }}</h3>
                        <div class="row">
                            <div class="col-xl-7 col-lg-9">

                                <div class="form-group">
                                    <label for="form_phone_number">{{ __('main.form.phone_number') }} <span
                                            class="text-danger">*</span></label>
                                    <input id="form_phone_number" type="text"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           name="phone_number"
                                           value="{{ old('phone_number') ?? $shop->phone_number }}" required
                                    >
                                    @error('phone_number')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="form_email">{{ __('main.form.email') }} <span
                                            class="text-danger">*</span></label>
                                    <input id="form_email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') ?? $shop->email }}" required
                                    >
                                    @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="form_address">{{ __('main.address') }}</label>
                                    <textarea id="form_address"
                                              class="form-control @error('address') is-invalid @enderror"
                                              name="address"
                                    >{{ old('address') ?? $shop->address }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn_theme btn_4">
                            {{ __('main.form.to_save') }}
                        </button>
                    </div>

                </form>


            </div>

            <div class="order-md-1 col-md-4 col-lg-3">
                @include('partials.sidebar_profile')
            </div>

        </div>

    </div>

@endsection
