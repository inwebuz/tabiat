@extends('layouts.app')
@section('seo_title', __('main.nav.login'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.nav.login') }}</h1>
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

                        <h3>{{ __('main.login_to_account') }}</h3>

                        <form action="{{ route('login') }}" method="POST" >
                            @csrf

                            <div class="form-group">
                                <label for="email">{{ __('main.phone_number') }}</label>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('main.password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('main.remember_me') }}?
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn_theme btn_3">
                                    {{ __('main.to_login') }}
                                </button>

                                <div>
                                    <a class="lost_pass" href="{{ route('password.phone') }}">
                                        {{ __('main.nav.forgot_password') }}
                                    </a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
                <div class="col-lg-6 order-lg-1 theme-bg2 text-center d-flex justify-content-center align-items-center">
                    <div class="login_part_text">
                        <h2>{{ __('main.dont_have_an_account') }}?</h2>
                        <a class="btn_theme btn_3" href="{{ route('register') }}">{{ __('main.nav.register') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Box Area =================-->

@endsection
