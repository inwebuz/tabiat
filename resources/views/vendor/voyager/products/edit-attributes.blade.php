@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Атрибуты товара')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-categories"></i>
        Атрибуты товара - {{ $product->name }}
    </h1>
@stop

@section('content')
    <div class="container-fluid" style="margin-bottom: 20px;">
        <a href="{{ route('voyager.products.edit', $product->id) }}" class="btn btn-sm btn-info m-5">
            <i class="voyager-angle-left"></i>
            <span class="hidden-xs hidden-sm">Продукт</span>
        </a>
        <a href="{{ route('voyager.products.variants', $product->id) }}" class="btn btn-sm btn-info m-5">
            <i class="voyager-lab"></i>
            <span class="hidden-xs hidden-sm">Варианты</span>
        </a>
    </div>

    <div class="page-content edit-add container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ route('voyager.products.attributes.update', $product->id) }}"
                            method="POST" enctype="multipart/form-data">

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <div class="form-group">
                                <h4>Выберите атрибут</h4>
                                <div class="row">
                                    <div class="col-sm-4 mb-10">
                                        <select name="all_attributes" id="all_attributes" class="form-control select2">
                                            <option value="">Нет</option>
                                            @foreach($attributes as $attribute)
                                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="button" id="add_attribute" class="btn m-0 btn-primary">Добавить</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <h4 class="mb-20">Атрибуты</h4>
                                <div id="selected_attributes">
                                    @foreach($product->attributes as $attribute)
                                        <div class="removable-block mb-15">
                                            <div id="product_attribute_{{ $attribute->id }}" class="mb-5">
                                                <h5>{{ $attribute->name }} <span class="remove-block pull-right cursor-pointer">&times;</span></h5>
                                                <select name="attributes[{{ $attribute->id }}][values][]" id="product_attributes_select_{{ $attribute->id }}" class="form-control select2" multiple>
                                                    @foreach($attribute->attributeValues as $attributeValue)
                                                        <option value="{{ $attributeValue->id }}" @if(in_array($attributeValue->id, $attributeValueIds)) selected @endif>{{ $attributeValue->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label>
                                                    <input type="checkbox" name="attributes[{{ $attribute->id }}][used_for_variations]" @if($attribute->pivot->used_for_variations) checked @endif>
                                                    <span>Использовать для вариантов товара</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
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
                    <div class="removable-block mb-15">
                        <div id="${productAttributeContainerId}" class="mb-5">
                            <h5>${attributeName} <span class="remove-block pull-right cursor-pointer">&times;</span></h5>
                            <select name="attributes[${attribute}][values][]" id="${productAttributesSelectId}" class="form-control select2" multiple></select>
                        </div>
                        <div>
                            <label>
                                <input type="checkbox" name="attributes[${attribute}][used_for_variations]">
                                <span>Использовать для вариантов товара</span>
                            </label>
                        </div>
                    </div>
                `);
                initSelects();

                getAttributeValues(attribute).then(values => {
                    for (let value of values) {
                        $('#' + productAttributesSelectId).append(`
                            <option value="${value.id}">${value.name}</option>
                        `);
                    }
                    initSelects()
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
@stop
