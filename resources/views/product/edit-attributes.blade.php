@extends('layouts.app')

@section('seo_title', __('main.attributes'))

@section('content')

    <!-- slider Area Start-->
    <div class="slider-area ">
        <div class="single-slider slider-height2 d-flex align-items-center" data-background="/images/bg/standard.jpg">
            <div class="container">
                <div class="hero-cap text-center">
                    <h1 class="main-header">{{ __('main.attributes') }}</h1>
                    @include('partials.breadcrumbs')
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->

    <div class="container py-5">

        <div class="mb-4">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info">
                {{ __('main.back') }}
            </a>
            <a href="{{ route('products.variants', $product->id) }}" class="btn btn-sm btn-info">
                {{ __('main.variants') }}
            </a>
        </div>

        @if(Session::has('message'))
            <div class="alert alert-success">
                {{ Session::get('message') }}
            </div>
        @endif

        <!-- form start -->
        <form action="{{ route('products.attributes.update', $product->id) }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <h4>{{ __('main.choose_attribute') }}</h4>
                <div class="row">
                    <div class="col-sm-4 mb-10">
                        <select name="all_attributes" id="all_attributes" class="form-control select2">
                            <option value="">{{ __('main.choose') }}</option>
                            @foreach($attributes as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" id="add_attribute" class="btn m-0 btn-primary">{{ __('main.add') }}</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <h4 class="mb-20">{{ __('main.attributes') }}</h4>
                <div id="selected_attributes">
                    @foreach($product->attributes as $attribute)
                        <div class="removable-block mb-4">
                            <div id="product_attribute_{{ $attribute->id }}" class="mb-1">
                                <h5>{{ $attribute->name }} <span class="remove-block float-right cursor-pointer">&times;</span></h5>
                                <select name="attributes[{{ $attribute->id }}][values][]" id="product_attributes_select_{{ $attribute->id }}" class="form-control select2" multiple>
                                    @foreach($attribute->attributeValues as $attributeValue)
                                        <option value="{{ $attributeValue->id }}" @if(in_array($attributeValue->id, $attributeValueIds)) selected @endif>{{ $attributeValue->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" name="attributes[{{ $attribute->id }}][used_for_variations]" @if($attribute->pivot->used_for_variations) checked @endif>
                                    <span>{{ __('main.use_for_product_variations') }}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('main.form.to_save') }}</button>
            </div>

        </form>

    </div>
@endsection

@section('scripts')
    <script>
        $(function () {

            $('#add_attribute').on('click', function(e){
                e.preventDefault();
                let container = $('#selected_attributes');
                let attribute = $('#all_attributes').val();
                let attributeName = $('#all_attributes option:selected').text();
                let productAttributeContainerId = 'product_attribute_' + attribute;
                let productAttributesSelectId = 'product_attributes_select_' + attribute;

                // attribute is empty or already selected
                if (!attribute || $('#' + productAttributeContainerId).length) {
                    return;
                }

                container.append(`
                    <div class="removable-block mb-4">
                        <div id="${productAttributeContainerId}" class="mb-1">
                            <h5>${attributeName} <span class="remove-block float-right cursor-pointer">&times;</span></h5>
                            <select name="attributes[${attribute}][values][]" id="${productAttributesSelectId}" class="form-control select2" multiple></select>
                        </div>
                        <div>
                            <label>
                                <input type="checkbox" name="attributes[${attribute}][used_for_variations]">
                                <span>{{ __('main.use_for_product_variations') }}</span>
                            </label>
                        </div>
                    </div>
                `);

                getAttributeValues(attribute).then(values => {
                    for (let value of values) {
                        $('#' + productAttributesSelectId).append(`
                            <option value="${value.id}">${value.name}</option>
                        `);
                    }
                });

            });

            function getAttributeValues(attribute) {
                return fetch('/api/attributes/' + attribute + '/attribute-values')
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            return [];
                        }
                    })
                    .then(result => result);
            }
        });
    </script>
@endsection
