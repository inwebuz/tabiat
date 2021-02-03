@extends('layouts.app')

@section('content')
    <!-- ================ start banner area ================= -->
    <section class="small-top-area">
        <div class="container h-100">
            <div class="small-top-content">
                <div class="text-center">
                    <h1 class="main-header">{{ __('main.nav.reset_password') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </section>
    <!-- ================ end banner area ================= -->

    <!--================Login Box Area =================-->
    <section class="login_box_area section-margin-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login_form_inner">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <label for="email">{{ __('main.form.email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('main.new_password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">{{ __('main.password_confirmation') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required >
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="button button-register">
                                    {{ __('main.form.send') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
