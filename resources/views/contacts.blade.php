@extends('layouts.app')

@section('seo_title', $page->seo_title ? $page->seo_title : $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->name, 'bg' => $page->bg])

    <section class="section-block pt-4">

        <div class="container">

            @include('partials.breadcrumbs')

            <div class="row mb-5">
                <div class="col-lg-6 order-lg-2 mb-5 mb-lg-0">

                    <h3 class="contact-title mb-4">{{ __('main.our_contacts') }}</h3>

                    <div class="media contact-info mb-3">
                        <div class="media-body">
                            <svg width="20" height="20">
                                <use xlink:href="#marker"></use>
                            </svg>
                            <span>{{ $address }}</span>
                        </div>
                    </div>
                    <div class="media contact-info mb-3">
                        <div class="media-body">
                            <svg width="20" height="20">
                                <use xlink:href="#phone"></use>
                            </svg>
                            <span><a href="tel:{{ Helper::phone(setting('contact.phone')) }}" class="black-link">{{ setting('contact.phone') }}</a></span>
                        </div>
                    </div>
                    <div class="media contact-info mb-3">
                        <div class="media-body">
                            <svg width="20" height="20">
                                <use xlink:href="#mail"></use>
                            </svg>
                            <span><a href="mailto:{{ setting('contact.email') }}" class="black-link">{{ setting('contact.email') }}</a></span>
                        </div>
                    </div>

                    <div class="contact-map mt-4">
                        {!! setting('contact.map') !!}
                    </div>


                </div>
                <div class="col-lg-6 order-lg-1">

                    <h3 class="contact-title">{{ __('main.write_us') }}</h3>

                    <form class="contact-form" method="post"  action="{{ route('contacts.send') }}">

                        @csrf

                        <div class="form-result"></div>

                        <div class="form-group">
                            <label for="form_name">{{ __('main.form.your_name') }} *</label>
                            <input class="form-control" name="name" id="form_name" type="text">
                        </div>

                        <div class="form-group">
                            <label for="form_phone">{{ __('main.form.phone') }} *</label>
                            <input class="form-control" name="phone" id="form_phone" type="text">
                        </div>
                        <div class="form-group">
                            <label for="form_message">{{ __('main.form.message') }} *</label>
                            <textarea class="form-control" name="message" id="form_message" rows="4"></textarea>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary px-4">{{ __('main.form.send') }}</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>

    </section>

@endsection
