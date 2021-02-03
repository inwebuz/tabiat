@extends('layouts.app')

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.verify_phone_number') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="login_part container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login_part_inner">
                    <div class="login_part_form">

                        <form method="POST" action="{{ route('register.verify') }}">

                            @csrf

                            <input type="hidden" name="phone_number" value="{{ $phone_number }}">

                            <div class="form-group">
                                <label for="verify_code">{{ __('main.enter_verify_code') }}</label>
                                <input id="verify_code" type="text"
                                       class="form-control @error('verify_code') is-invalid @enderror"
                                       name="verify_code" required autofocus maxlength="6">

                                @error('verify_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn_theme btn_3">
                                {{ __('main.form.to_confirm') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
