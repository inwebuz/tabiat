<section class="content-block feedback-block">
    <div class="container">
        <h2 class="main-header text-center">
            {{ __('main.feedback_title') }}
        </h2>
        <div class="feedback-form-container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <form action="{{ route('contacts.send') }}" class="contact-form" method="post">
                        @csrf
                        <input type="hidden" name="type" value="{{ \App\Contact::TYPE_CONTACT }}">
                        <div class="form-result"></div>
                        <div class="row no-gutters">
                            <div class="col-lg-4">
                                <div class="form-group px-2">
                                    <input type="text" class="form-control form-control-lg form-control-white" name="name" placeholder="{{ __('main.form.your_name') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group px-2">
                                    <input type="text" class="form-control form-control-lg form-control-white" name="phone" placeholder="{{ __('main.form.phone_number') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group px-2">
                                    <button type="submit" class="btn btn-lg btn-secondary btn-block">{{ __('main.form.send') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-none d-lg-block px-2">
                            <input type="text" class="form-control form-control-lg form-control-light" name="message" placeholder="{{ __('main.form.message') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
