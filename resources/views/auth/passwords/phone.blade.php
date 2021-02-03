@extends('layouts.app')

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.nav.reset_password') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <!--================Login Box Area =================-->
    <div class="login_part container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login_part_inner">
                    <div class="login_part_form">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.phone') }}">
                            @csrf

                            <div class="form-group">
                                <label for="phone_number">
                                    <span>{{ __('main.phone_number') }}</span>
                                    <small class="text-muted">({{ __('main.phone_number_example') }})</small>
                                </label>

                                <input id="phone_number" type="text" class="phone-input-mask form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="phone_number" autofocus pattern="^998\d{9}" maxlength="12">

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn_theme btn_3">
                                {{ __('main.form.send') }}
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
