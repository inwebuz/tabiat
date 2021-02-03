@extends('layouts.app')

@section('seo_title', __('main.checkout'))
@section('meta_description', '')
@section('meta_keywords', '')

@section('content')

    @include('partials.page_top', ['title' => __('main.checkout'), 'bg' => ''])

    <section class="content-block">
        <div class="container">
            @if(!$cart->isEmpty())
                <div class="checkout_form">
                    <form action="{{ route('order.add') }}" method="post" id="checkout-form">

                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-10">
                                <div class="box-shadow">
                                    <h4 class="mb-4">{{ __('main.your_order') }}</h4>
                                    <div class="table-responsive">
                                        <table class="table standard-list-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('main.product') }}</th>
                                                    <th>{{ __('main.price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cartItems as $cartItem)
                                                <tr>
                                                    <td>
                                                        <a class="black-link" href="{{ $cartItem->associatedModel->url }}" target="_blank">{{ $cartItem->name }}</a>
                                                        <strong> × {{ $cartItem->quantity }}</strong>
                                                    </td>
                                                    <td class="text-nowrap"> {{ Helper::formatPrice($cartItem->getPriceSum()) }} </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        <strong>{{ __('main.total') }}</strong>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <strong>{{ Helper::formatPrice($cart->getTotal()) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-10">
                                <div class="box-shadow">

                                    <h4 class="mb-4">{{ __('main.contact_information') }}</h4>

                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('main.form.your_name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" value="{{ old('name', optional(auth()->user())->name) }}" required>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">
                                                <span>{{ __('main.form.phone_number') }}</span>
                                                <span class="text-danger">*</span>
                                                <small class="text-muted">({{ __('main.phone_number_example') }})</small>
                                            </label>

                                            <input type="text" name="phone_number" class="phone-input-mask form-control  @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', optional(auth()->user())->phone_number) ?? '' }}" maxlength="12" required>
                                            @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">
                                                <span>{{ __('main.form.email') }}</span>
                                            </label>

                                            <input type="email" name="email" class="phone-input-mask form-control  @error('email') is-invalid @enderror" value="{{ old('email', optional(auth()->user())->email) ?? '' }}">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">{{ __('main.address') }} <span class="text-danger">*</span></label>
                                            <textarea name="address" class="form-control  @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">{{ __('main.communication_method') }} <span class="text-danger">*</span></label>
                                            @php
                                                $checkedCommunicationMethodKey = old('communication_method') ?: 0;
                                            @endphp
                                            @foreach($communicationMethods as $communicationMethodKey => $communicationMethod)
                                                <div class="form-check">
                                                    <input class="form-check-input" id="communication_method_{{ $communicationMethodKey }}" type="radio" name="communication_method" value="{{ $communicationMethodKey }}" @if($checkedCommunicationMethodKey == $communicationMethodKey) checked @endif required>
                                                    <label class="form-check-label" for="communication_method_{{ $communicationMethodKey }}">
                                                        {{ $communicationMethod }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error('communication_method')
                                            <div class="invalid-feedback d-block" role="alert">
                                                <strong>{{ __('main.choose_value') }}</strong>
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">{{ __('main.payment_method') }} <span class="text-danger">*</span></label>
                                            @php
                                                $checkedPaymentMethodKey = old('communication_method') ?: 1; // default = 1 - cash
                                            @endphp
                                            @foreach(\App\Order::paymentMethods() as $paymentMethodKey => $paymentMethod)
                                                <div class="form-check">
                                                    <input class="form-check-input" id="payment_method_{{ $paymentMethodKey }}" type="radio" name="payment_method_id" value="{{ $paymentMethodKey }}" @if($checkedPaymentMethodKey == $paymentMethodKey) checked @endif required>
                                                    <label class="form-check-label" for="payment_method_{{ $paymentMethodKey }}">
                                                        {{ $paymentMethod }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error('payment_method_id')
                                            <div class="invalid-feedback d-block" role="alert">
                                                <strong>{{ __('main.choose_value') }}</strong>
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">{{ __('main.form.message') }}</label>
                                            <textarea name="message" class="form-control  @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                            @error('message')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="form-group d-none">
                                            <label class="control-label">{{ __('main.order_type') }} <span class="text-danger">*</span></label></label>
                                            @php
                                                $checkedOrderTypeKey = old('type') ?: 0;
                                            @endphp
                                            @foreach($orderTypes as $orderTypeKey => $orderType)
                                                <div class="form-check">
                                                    <input class="form-check-input" id="order_type_{{ $orderTypeKey }}" type="radio" name="type" value="{{ $orderTypeKey }}" @if($checkedOrderTypeKey == $orderTypeKey) checked @endif >
                                                    <label class="form-check-label" for="order_type_{{ $orderTypeKey }}">
                                                        {{ $orderType }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error('type')
                                            <div class="invalid-feedback d-block" role="alert">
                                                <strong>{{ __('main.choose_value') }}</strong>
                                            </div>
                                            @enderror
                                        </div> --}}

                                        {{-- <div class="form-group d-none">

                                            <label for="create_an_account_checkbox" data-toggle="collapse" data-target="#create_an_account_block" aria-controls="create_an_account_block">
                                                <input id="create_an_account_checkbox" type="checkbox" name="create_an_account" />
                                                Create an account?
                                            </label>

                                            <div id="create_an_account_block" class="collapse one">
                                                <div class="card-body1">
                                                    <label> Account password <span>*</span></label>
                                                    <input name="password" type="password" class="form-control">
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="form-group">
                                            <input id="public_offer" name="public_offer" type="checkbox" required>
                                            <label for="public_offer">{!! __('main.accept_the_terms', ['url' => '<a href="' . $publicOfferPage->url . '" target="_blank" class="text-primary">' . __('main.of_public_offer') . '</a>']) !!}  <span class="text-danger">*</span></label>
                                            @error('public_offer')
                                            <div class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="payment_method">
                                            <div>
                                                <button type="submit" class="btn btn-lg btn-outline-secondary">{{ __('main.place_order') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            @else
                <div class="my-5 lead text-center">{{ __('main.cart_is_empty') }}</div>
            @endif
        </div>
    </div>

    @include('partials.contact_form')

@endsection

@section('scripts')
{{-- <script>
    document.addEventListener('DOMContentLoaded', function(){

        validate.validators.presence.message = "{{ __('main.required_field') }}";
        let form = $('#checkout-form');
        let button = form.find('[type=submit]');
        let constraints = {
            name: {
                presence: true,
            },
            phone_number: {
                presence: true,
                format: {
                    pattern: /^998\d{9}$/,
                    message: "{{ __('main.regex_error') }}"
                }
            },
            communication_method: {
                presence: true,
            },
            type: {
                presence: true,
            },
            public_offer: {
                presence: true,
                inclusion: {
                    within: [true],
                    message: "{{ __('main.required_field') }}"
                }
            }
        };
        let options = {
            fullMessages: false
        };

        // validateCheckoutForm();

        form.find('input, select, textarea').on('change paste', function(){
            validateCheckoutForm($(this));
        });
        form.on('submit', validateCheckoutForm);

        function validateCheckoutForm(elem = false) {
            let errors = validate(form[0], constraints, options);
            if (errors) {
                // error
                button.addClass('disabled');
                if (elem) {
                    showError(elem, errors)
                } else {
                    showErrors(errors);
                }
            } else {
                // success
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
                button.removeClass('disabled');
            }
        }

        function showError(elem, errors) {
            let formGroup = elem.closest('.form-group');
            let elemName = elem.attr('name');

            // clean form group
            formGroup.find('.invalid-feedback').remove();
            elem.removeClass('is-invalid');

            // show error
            if (errors[elemName] != undefined) {
                // has error
                elem.addClass('is-invalid');
                formGroup.append(`<div class="invalid-feedback d-block">${errors[elemName][0]}</div>`);
            }
        }

        function showErrors(errors) {
            button.addClass('disabled');
            for(let i in errors) {
                let elem = form.find('[name="' + i + '"]');
                showError(elem, errors);
            }
        }
    });
</script> --}}
@endsection
