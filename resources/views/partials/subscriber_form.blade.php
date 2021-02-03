<div class="subscriber-form-block">
    <div class="container">
        <form action="{{ route('subscriber.subscribe') }}" method="post" class="subscriber-form">
            <div class="form-result"></div>
            <div class="row">
                <div class="col-lg-4">
                    <input type="text" name="name" class="form-control" placeholder="{{ __('main.form.your_name') }}" required>
                </div>
                <div class="col-lg-4">
                    <input type="email" name="email" class="form-control" placeholder="{{ __('main.form.your_email') }}" required>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-block btn-primary" type="submit">{{ __('main.to_subscribe') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
