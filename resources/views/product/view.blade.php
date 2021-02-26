@extends('layouts.app')

@section('seo_title', $product->seo_title ? $product->seo_title : $product->name)
@section('meta_description', $product->meta_description)
@section('meta_keywords', $product->meta_keywords)
@section('body_class', 'product-page')

@section('content')

    @can('edit', $product->getModel())
        <div class="container">
            <div class="my-4">
                <a href="{{ url('admin/products/' . $product->id . '/edit') }}" class="btn btn-lg btn-primary" target="_blank">Редактировать (SKU: {{ $product->sku }}, ID: {{ $product->id }})</a>
            </div>
        </div>
    @endcan

    @include('partials.page_top', ['bg' => '', 'title' => $category->name]);

    <div class="container">
        @include('partials.breadcrumbs')
    </div>

    <section class="section-block pt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-lg-2 main-block">

                    <div class="product-page-image mb-3">
                        <a href="{{ $product->img }}" data-fancybox="gallery">
                            <img src="{{ $product->large_img }}" class="img-fluid" alt="{{ $product->name }}">
                        </a>
                    </div>

                    <h2 class="product-header">{{ $product->name }}</h2>

                    @if ($product->description)
                        <div class="box mt-1">
                            <div class="product-page-description">
                                {{ $product->description }}
                            </div>
                        </div>
                    @endif

                    <div class="box mt-4">
                        <button class="btn btn-lg btn-primary" data-toggle="modal" data-target="#contact-modal">
                            {{ __('main.to_order') }}
                        </button>
                    </div>

                    @if ($product->body)
                        <div class="box mt-4">
                            <div class="text-block">
                                {!! $product->body !!}
                            </div>
                        </div>
                    @endif

                </div>
                <div class="col-lg-3 order-lg-1 side-block mt-5 mt-lg-0 text-center text-lg-left">
                    @include('partials.subcategories_side_box')
                    @if ($banner)
                        <div class="side-box sticky-top-100">
                            @if ($banner->url)
                                <a href="{{ $banner->url }}">
                                    <img src="{{ $banner->img }}" alt="{{ $banner->description }}" class="img-fluid">
                                </a>
                            @else
                                <img src="{{ $banner->img }}" alt="{{ $banner->description }}" class="img-fluid">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if(!$similar_products->isEmpty())
        <section class="section-block bg-light-green section-pattern-top section-pattern-bottom">
            <div class="container">
                <h2 class="main-header text-center text-md-left">{{ __('main.similar_products') }}</h2>

                <div class="products-list">
                    <div class="row">
                        @foreach($similar_products as $similar_product)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                @include('partials.product_one', ['product' => $similar_product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="container">
            <div class="py-4 text-block">
                {!! $product->specifications !!}
            </div>
        </div>
    </section>


@endsection

@section('scripts')
    <script>
        $(function(){
            let addToCartBtn = $('.add-to-cart-btn');
            let productImagesSlider = $('.product-image-slider');
            productImagesSlider.length && productImagesSlider.slick({
                slidesToShow: 2,
                slidesToScroll: 2,
                prevArrow: null,
                nextArrow: productImagesSlider.closest('.product-image-slider-container').find('.arrow-next')
            });

            // product variants control
            let productVariants = {};
            // create product variants obj
            $('.product-variant-item').each(function(){
                let combinationName = $(this).attr('data-combination').split(',');
                let combinationObj = [];
                for(let i in combinationName) {
                    combinationObj.push(+combinationName[i]);
                }
                productVariants[$(this).attr('data-id')] = combinationObj;
            });

            // all product variant selects
            let productVariantAttributes = $('.product-variant-attribute');
            let productVariantAttributesQuantity = productVariantAttributes.length;
            let productVariantAttributeValues = $('.product-variant-attribute-value');

            // product variant attribute checked event
            productVariantAttributeValues.on('click', function(){
                productVariantAttributeValueClickHandler($(this));
            });

            function productVariantAttributeValueClickHandler(attributeValueObj) {
                let selectedProductVariantID;
                let selectedAttributeValueIDs = [];
                let attributeValue = attributeValueObj;
                let attributeValueID = +attributeValue.data('id');
                let attributeID = +attributeValue.data('attribute-id');
                let attribute = productVariantAttributes.filter('[data-id="' + attributeID + '"]');
                let otherAttributes = productVariantAttributes.not('[data-id="' + attributeID + '"]');

                if (attributeValue.hasClass('disabled')) {
                    return false;
                }
                if (attributeValue.hasClass('active')) {
                    attributeValue.removeClass('active');
                } else {
                    attribute.find('.product-variant-attribute-value').removeClass('active');
                    attributeValue.addClass('active');
                }

                // set selected attribute value ids
                productVariantAttributeValues.filter('.active').each(function(){
                    selectedAttributeValueIDs.push(+$(this).data('id'));
                });

                // check if all attributes has active values
                let allAttributesHasActiveValues = (selectedAttributeValueIDs.length == productVariantAttributesQuantity) ? true : false;

                // if allAttributesHasActiveValues is true - get product variant, enable cart button
                if (allAttributesHasActiveValues) {
                    // get selected product variant id
                    selectedProductVariantID = getSelectedProductVariantID(selectedAttributeValueIDs);
                    if (selectedProductVariantID) {
                        applySelectedProductVariantID(selectedProductVariantID);
                    } else {
                        // error happened
                        productVariantAttributeValues.removeClass('active').removeClass('disabled');
                        productVariantAttributeValueClickHandler(attributeValue);
                    }
                } else { // not all attributes selected - disable cart button, disable unavailable values, disable stock texts
                    // disable cart
                    addToCartBtn
                        .addClass('disabled')
                        .attr('data-price', '')
                        .attr('data-base-price', '')
                        .attr('data-name', '')
                        .attr('data-variant-id', '');

                    // disable unavailable values
                    let availableProductVariants = [];
                    let availableProductVariantAttributeValueIDs = [];
                    for (let i in productVariants) {
                        let variantAvailable = true;
                        for (let selectedAttributeValueID of selectedAttributeValueIDs) {
                            if ( !productVariants[i].includes(selectedAttributeValueID) ) {
                                variantAvailable = false;
                                break;
                            }
                        }
                        if (variantAvailable) {
                            availableProductVariantAttributeValueIDs = availableProductVariantAttributeValueIDs.concat(productVariants[i]);
                        }
                    }
                    productVariantAttributeValues.removeClass('disabled');
                    productVariantAttributeValues.each(function(){
                        if (!availableProductVariantAttributeValueIDs.includes(+$(this).data('id'))) {
                            $(this).addClass('disabled');
                        }
                    });

                    // disable stock values
                    $('.product-page-stock').addClass('d-none');
                    $('.product-page-stock-text').addClass('d-none');
                }
            }

            // get selected product variant id or false
            function getSelectedProductVariantID(selectedAttributeValueIDs) {
                let selectedProductVariantID = false;
                for (let i in productVariants) {
                    let variantSelected = true;
                    for (let selectedAttributeValueID of selectedAttributeValueIDs) {
                        if ( !productVariants[i].includes(selectedAttributeValueID) ) {
                            variantSelected = false;
                            break;
                        }
                    }
                    if (variantSelected) {
                        selectedProductVariantID = i;
                        break;
                    }
                }
                return selectedProductVariantID;
            }

            // apply selected product variant
            function applySelectedProductVariantID(selectedProductVariantID) {
                let selectedProductVariant = $('.product-variant-item[data-id="' + selectedProductVariantID + '"]');
                if (selectedProductVariant.length && addToCartBtn.length) {
                    addToCartBtn
                        .removeClass('disabled')
                        .attr('data-price', selectedProductVariant.attr('data-price'))
                        .attr('data-base-price', selectedProductVariant.attr('data-base-price'))
                        .attr('data-name', selectedProductVariant.attr('data-name'))
                        .attr('data-variant-id', selectedProductVariant.attr('data-id'));
                    $('.product_page_current_price').text(selectedProductVariant.attr('data-formatted-price'));
                    let oldPriceBlock = $('.product_page_old_price');
                    if (oldPriceBlock.length) {
                        oldPriceBlock.text(selectedProductVariant.attr('data-formatted-base-price'));
                        if (selectedProductVariant.attr('data-price') >= selectedProductVariant.attr('data-base-price')) {
                            oldPriceBlock.hide();
                        } else {
                            oldPriceBlock.show();
                        }
                    }

                    $('.product_page_in_stock').text(selectedProductVariant.attr('data-in-stock'));
                    $('.product-page-stock').removeClass('d-none');
                    $('.product-page-stock-text').removeClass('d-none');
                    if (selectedProductVariant.attr('data-in-stock') > 0) {
                        addToCartBtn.removeClass('disabled');
                        $('.product_page_in_stock_text').addClass('d-inline-block').removeClass('d-none');
                        $('.product_page_not_in_stock_text').addClass('d-none').removeClass('d-inline-block');
                    } else {
                        addToCartBtn.addClass('disabled');
                        $('.product_page_in_stock_text').addClass('d-none').removeClass('d-inline-block');
                        $('.product_page_not_in_stock_text').addClass('d-inline-block').removeClass('d-none');
                    }
                }
            }

            // reset checked attribute values
            function resetCheckedValues() {
                productVariantAttributeValues.prop('checked', false).prop('disabled', false).removeClass('disabled');
            }

        });
    </script>
@endsection
