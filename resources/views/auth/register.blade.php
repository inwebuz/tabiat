@extends('layouts.app')
@section('seo_title', __('main.nav.register'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.nav.register') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <!--================Login Box Area =================-->
    <section class="login_part my-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 order-lg-2 white-bg">
                    <div class="login_part_form">

                        <h3>{{ __('main.create_an_account') }}</h3>

                        <form action="{{ route('register') }}" method="POST" id="register_form" >
                            @csrf
                            <div class="form-group">
                                <label for="first_name">{{ __('main.first_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="last_name">{{ __('main.last_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- <div class="form-group">
                                <label for="phone_number">{{ __('main.phone_number') }} <small>({{ __('main.phone_number_example') }})</small> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" pattern="^998\d{9}" maxlength="12" required>
                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                            <div class="form-group">
                                <label for="email">{{ __('main.email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('main.password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">{{ __('main.password_confirmation') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" value="submit" class="btn_theme btn_3">{{ __('main.to_register') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-lg-6 order-lg-1 theme-bg2 text-center d-flex justify-content-center align-items-center">
                    <div class="login_part_text">
                        <h2>{{ __('main.already_have_an_account') }}?</h2>
                        <a class="btn_theme btn_3" href="{{ route('login') }}">{{ __('main.nav.login') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Box Area =================-->

@endsection
