@extends('layouts.app')

@section('seo_title', $page->seo_title ? $page->seo_title : $page->name)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

    @include('partials.page_top', ['title' => $page->name, 'bg' => $page->bg])

    <section class="content-block">

        <div class="container">


            <div class="row mb-5">
                <div class="col-lg-6 order-lg-2 mb-5 mb-lg-0">

                    <h3 class="contact-title mb-4">{{ __('main.our_contacts') }}</h3>

                    <div class="media contact-info">
                        <i class="fa fa-map-marker mr-3"></i>
                        <div class="media-body">
                            <span>{{ setting('contact.address') }}</span>
                        </div>
                    </div>
                    <div class="media contact-info">
                        <i class="fa fa-phone mr-3"></i>
                        <div class="media-body">
                            <span><a href="tel:{{ Helper::phone(setting('contact.phone')) }}" class="black-link">{{ setting('contact.phone') }}</a></span>
                        </div>
                    </div>
                    <div class="media contact-info">
                        <i class="fa fa-envelope mr-3"></i>
                        <div class="media-body">
                            <span><a href="mailto:{{ setting('contact.email') }}" class="black-link">{{ setting('contact.email') }}</a></span>
                        </div>
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
                            <button type="submit" class="btn btn-outline-secondary">{{ __('main.form.send') }}</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>

    </section>

    <div class="contact-map">
        {!! setting('contact.map') !!}
    </div>


@endsection
