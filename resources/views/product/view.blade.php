@extends('layouts.app')

@section('seo_title', $product->seo_title ? $product->seo_title : $product->name)
@section('meta_description', $product->meta_description)
@section('meta_keywords', $product->meta_keywords)

@section('content')

    @can('edit', $product->getModel())
        <div class="container">
            <div class="my-4">
                <a href="{{ url('admin/products/' . $product->id . '/edit') }}" class="btn btn-lg btn-primary" target="_blank">Редактировать (SKU: {{ $product->sku }}, ID: {{ $product->id }})</a>
            </div>
        </div>
    @endcan

    <section class="content-block mt-0">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="product-page-image mb-3">
                        <a href="{{ $product->img }}" data-fancybox="gallery">
                            <img src="{{ $product->large_img }}" class="img-fluid" alt="{{ $product->name }}">
                        </a>
                    </div>
                    <div class="product-image-slider-container position-relative">
                        <div class="product-image-slider">
                            @foreach($product->small_imgs as $smallImgKey => $smallImg)
                                <div class="product-image-slide">
                                    <a href="{{ $product->imgs[$smallImgKey] }}" class="d-block mx-2" data-fancybox="gallery">
                                        <img src="{{ $smallImg }}" class="img-fluid" alt="{{ $product->name }} {{ $smallImgKey }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <span class="arrow-next"><i class="fa fa-angle-right"></i></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="product-page-breadcrumbs">
                        @include('partials.breadcrumbs')
                    </div>
                    <h1 class="product-header">{{ $product->name }}</h1>
                    @if ($brand)
                        <div>
                            <strong>{{ __('main.brand') }}: <a href="{{ $brand->url }}">{{ $brand->name }}</a></strong>
                        </div>
                    @endif
                    <div class="product-page-price">
                        <span class="product_page_current_price current-price @if($product->getModel()->isDiscounted()) special-price @endif">
                            {{ Helper::formatPrice($product->current_price) }}
                        </span>
                        @if($product->getModel()->isDiscounted())
                            <span class="product_page_old_price old-price ml-1">
                                {{ Helper::formatPrice($product->price) }}
                            </span>
                        @endif
                    </div>

                    <div class="product-page-stock-box mt-4">
                        <span class="product-page-stock-text @if(!$productVariants->isEmpty()) d-none @endif">
                            <span class="product_page_in_stock_text bg-success text-light py-1 px-2 mr-2 @if ($productVariants->isEmpty() && $product->getModel()->isAvailable()) d-inline-block @else d-none @endif">
                                {{ __('main.in_stock') }}
                            </span>
                            <span class="product_page_not_in_stock_text bg-light text-dark py-1 px-2 mr-2 @if($productVariants->isEmpty() && !$product->getModel()->isAvailable()) d-inline-block @else d-none @endif">
                                {{ __('main.not_in_stock') }}
                            </span>
                        </span>
                        <span class="product-page-stock @if(!$productVariants->isEmpty()) d-none @endif">
                            {!! __('main.products_left2', ['quantity' => '<span class="product_page_in_stock">' . $product->in_stock . '</span>']) !!}
                        </span>
                    </div>



                    @if(!$productVariants->isEmpty())
                        @include('partials.product_variants_rows')
                    @endif

                    <div class="product-page-box">
                        <div class="row">
                            <div class="col-lg-6 col-xl-5 mb-4 mb-lg-0">
                                <!-- cart -->
                                <button type="button"
                                    class="btn btn-secondary btn-lg text-nowrap add-to-cart-btn @if(!$productVariants->isEmpty() ) disabled has-product-variants @elseif(!$product->getModel()->isAvailable()) disabled @endif" data-id="{{ $product->id }}"
                                    data-variant-id=""
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->current_price }}"
                                >
                                    {{ __('main.add_to_cart') }}
                                </button>
                                <!-- end cart -->
                            </div>
                            <div class="col-lg-6 col-xl-7 d-flex align-items-center">
                                <!-- wishlist -->
                                <button data-add-url="{{ route('wishlist.add') }}" data-remove-url="{{ route('wishlist.delete', $product->id) }}"
                                    class="@if(!app('wishlist')->get($product->id))add-to-wishlist-btn @else remove-from-wishlist-btn @endif wishlist-control-btn btn btn-link gray-link px-0" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->current_price }}"
                                    data-add-text="{!! __('main.icon_not_in_wishlist') !!} {{ __('main.add_to_wishlist') }}"
                                    data-delete-text="{!! __('main.icon_in_wishlist') !!} {{ __('main.delete_from_wishlist') }}">
                                    @if(!app('wishlist')->get($product->id))
                                        {!! __('main.icon_not_in_wishlist') !!} {{ __('main.add_to_wishlist') }}
                                    @else
                                        {!! __('main.icon_in_wishlist') !!} {{ __('main.delete_from_wishlist') }}
                                    @endif

                                </button>
                                <!-- end wishlist -->
                            </div>
                        </div>
                    </div>

                    @if ($product->description)
                        <div class="box mt-5">
                            <h5 class="small-header">{{ __('main.description') }}</h5>
                            <div class="text-block">
                                {{ $product->description }}
                            </div>
                        </div>
                    @endif

                    @if ($brand || !$attributes->isEmpty())
                        <div class="box mt-5">
                            <h5 class="small-header">{{ __('main.specifications') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless specifications-table">
                                    @if ($brand)
                                        <tr>
                                            <td>
                                                <span>{{ __('main.brand') }}</span>
                                            </td>
                                            <td>{{ $brand->name }}</td>
                                        </tr>
                                    @endif
                                    @if(!$attributes->isEmpty())
                                        @foreach($attributes as $attribute)
                                            <tr>
                                                <td>
                                                    <span>{{ $attribute->name }}</span>
                                                </td>
                                                <td>
                                                    @foreach($attribute->attributeValues as $attributeValue)
                                                        {{ $attributeValue->name }}@if(!$loop->last){{ ',' }}@endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </table>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </section>

    @if ($product->body)
        <section class="content-block">
            <div class="container">
                <div class="text-block">
                    {!! $product->body !!}
                </div>
            </div>
        </section>
    @endif

    @if(!$similar_products->isEmpty())
        <section class="content-block">
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

    @include('partials.contact_form')


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
