@extends('layouts.app')

@section('seo_title', $category->seo_title ? $category->seo_title : $category->name)
@section('meta_description', $category->meta_description)
@section('meta_keywords', $category->meta_keywords)
@section('body_class', 'category-page')

@section('content')

    @include('partials.page_top', ['bg' => '', 'title' => $category->name]);

    <section class="content-block mt-0">
        <div class="container">
            <form action="{{ $category->url }}" class="category-main-form">
                <div class="row">
                    <div class="col-lg-9 order-lg-2 main-block">
                        @if(!$products->isEmpty())
                            <div class="text-lg-right mb-4">
                                <div class="dropdown">
                                    <button class="btn btn-link px-0 black-link dropdown-toggle" type="button" id="change-sort-dropdown-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{!! __('main.sort.' . $sortCurrent) !!}</button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="change-sort-dropdown-btn">
                                        @foreach($sorts as $sort)
                                            <span class="dropdown-item cursor-pointer change-sort-dropdown-item @if($sortCurrent == $sort) disabled @endif" data-value="{{ $sort }}" >{!! __('main.sort.' . $sort) !!}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="sort" value="{{ $sortCurrent }}">
                            </div>
                            <div class="products-list">
                                <div class="row">
                                    @foreach($products as $product)
                                        <div class="col-xl-4 col-lg-4 col-md-6">
                                            @include('partials.product_one')
                                        </div>
                                    @endforeach
                                </div>
                                {!! $links !!}
                            </div>
                        @else
                            <div class="lead">
                                {{ __('main.no_products') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-3 order-lg-1 side-block">
                        @if(!$subcategories->isEmpty())
                            <div class="side-box">
                                <h3 class="box-header">{{ __('main.categories') }}</h3>
                                <ul class="side-box-list list-unstyled">
                                    @foreach($subcategories as $subcategory)
                                        <li>
                                            <a href="{{ $subcategory->url }}">{{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @php
                            $categoryPricesFrom = isset($categoryPrices['from']) ? (int)$categoryPrices['from'] : (int)$categoryPrices['min'];
                            $categoryPricesTo = isset($categoryPrices['to']) ? (int)$categoryPrices['to'] : (int)$categoryPrices['max'];
                            $categoryPricesMin = (int)$categoryPrices['min'];
                            $categoryPricesMax = (int)$categoryPrices['max'];
                        @endphp
                        <div class="side-box">
                            <h3 class="box-header">{{ __('main.price') }}</h3>
                            <div class="values">
                                <div class="text-nowrap"><span id="price-range-first"></span> {{ __('main.currency') }} - <span id="price-range-second"></span> {{ __('main.currency') }}</div>
                            </div>
                            <div class="px-2 pt-2">
                                <div class="price-range-slider" data-value-0="#price-range-first" data-value-1="#price-range-second" data-range="#third"></div>
                            </div>
                            <input type="hidden" id="price-range-first-input" name="price[from]" value="{{ isset($categoryPrices['from']) ? $categoryPricesFrom : $categoryPricesMin }}">
                            <input type="hidden" id="price-range-second-input" name="price[to]" value="{{ isset($categoryPrices['to']) ? $categoryPricesTo : $categoryPricesMax }}">

                            <div class="pt-3">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">{{ __('main.form.apply') }}</button>
                            </div>
                        </div>

                        @if(!$categoryAttributes->isEmpty())
                            @foreach($categoryAttributes as $attribute)
                                <div class="side-box">
                                    <h3 class="box-header mb-3">{{ $attribute->name }}</h3>
                                    <ul id="filter-attribute-{{ $attribute->id }}" class="side-box-list side-box-list-{{ $attribute->type }} list-unstyled">
                                        @php
                                            $maxVisibleValues = 5;
                                        @endphp
                                        @foreach($attribute->attributeValues as $key => $attributeValue)
                                            @php
                                                $isAttrValueActive = (!empty($attributes[$attribute->id]) && is_array($attributes[$attribute->id]) && in_array($attributeValue->id, $attributes[$attribute->id])) ? true : false;
                                            @endphp
                                            @if ($attribute->type == \App\Attribute::TYPE_BUTTONS)
                                                <li class="category-filter-row d-inline-block">
                                                    <div class="">
                                                        <input type="checkbox" name="attribute[{{ $attribute->id }}][]" value="{{ $attributeValue->id }}" class="d-none category-filter-checkbox" id="attribute_value_{{ $attributeValue->id }}" @if ($isAttrValueActive) checked @endif >
                                                        <label class="btn btn-outline-light mb-2 mr-1" for="attribute_value_{{ $attributeValue->id }}">{{ $attributeValue->name }}</label>
                                                    </div>
                                                </li>
                                            @elseif ($attribute->type == \App\Attribute::TYPE_COLORS)
                                                <li class="category-filter-row @if($key > $maxVisibleValues - 1) category-filter-row-visibility-changable d-none @endif">
                                                    <div class="">
                                                        <input type="checkbox" name="attribute[{{ $attribute->id }}][]" value="{{ $attributeValue->id }}" id="attribute_value_{{ $attributeValue->id }}" class="d-none category-filter-checkbox" @if ($isAttrValueActive) checked @endif >
                                                        <label for="attribute_value_{{ $attributeValue->id }}" class="cursor-pointer">
                                                            <span class="mr-1 color-button @if($attributeValue->is_light_color) color-button-light @endif" style="background-color: {{ $attributeValue->color }}"></span>
                                                            {{ $attributeValue->name }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @else
                                                <li class="category-filter-row">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="attribute[{{ $attribute->id }}][]" value="{{ $attributeValue->id }}" class="custom-control-input category-filter-checkbox" id="attribute_value_{{ $attributeValue->id }}" @if ($isAttrValueActive) checked @endif >
                                                        <label for="attribute_value_{{ $attributeValue->id }}" class="custom-control-label cursor-pointer">{{ $attributeValue->name }}</label>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    @if($attribute->type != \App\Attribute::TYPE_BUTTONS && $key > $maxVisibleValues - 1)
                                        <a href="#filter-attribute-{{ $attribute->id }}" class="gray-link side-box-list-switch">
                                            <span class="active-hidden">{{ __('main.more') }}...</span>
                                            <span class="active-visible">{{ __('main.less') }}...</span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        @if(!$categoryBrands->isEmpty())
                            <div class="side-box">
                                <h3 class="box-header mb-3">{{ __('main.brands') }}</h3>
                                <ul id="filter-brands" class="side-box-list side-box-list-0 list-unstyled">
                                    @foreach($categoryBrands as $key => $brand)
                                        @php
                                            $isBrandActive = (!empty($brands) && is_array($brands) && in_array($brand->id, $brands)) ? true : false;
                                        @endphp
                                        <li class="category-filter-row @if($key > $maxVisibleValues - 1) category-filter-row-visibility-changable d-none @endif">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="brand[]" value="{{ $brand->id }}" class="custom-control-input category-filter-checkbox" id="brand_{{ $brand->id }}" @if ($isBrandActive) checked @endif >
                                                <label for="brand_{{ $brand->id }}" class="custom-control-label cursor-pointer">{{ $brand->name }}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($key > $maxVisibleValues - 1)
                                    <a href="#filter-brands" class="gray-link side-box-list-switch">
                                        <span class="active-hidden">{{ __('main.more') }}...</span>
                                        <span class="active-visible">{{ __('main.less') }}...</span>
                                    </a>
                                @endif
                            </div>
                        @endif


                        <div class="side-box">
                            <button class="btn btn btn-outline-secondary" type="submit">{{ __('main.form.apply') }}</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </section>

    @include('partials.contact_form')

@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('js/TweenMax.min.js') }}"></script>
    <script>
        $(function(){
            let form = $('.category-main-form');
            $('.change-sort-dropdown-item').on('click', function(e){
                e.preventDefault();
                if ($(this).hasClass('active')) {
                    return;
                }
                $('#change-sort-dropdown-btn').text($(this).text());
                $(this).parent().find('.active').removeClass('active');
                $(this).addClass('active');
                let newValue = $(this).data('value');
                form.find('[name="sort"]').val(newValue);
                form.submit();
            });
            $('.side-box-list-switch').on('click', function(e){
                e.preventDefault();
                $(this).toggleClass('active');
                let targetIdHash = $(this).attr('href');
                let target = $(targetIdHash);
                if (target.length) {
                    target.find('.category-filter-row-visibility-changable').toggleClass('d-none');
                }
            });

            // price range
            $('.price-range-slider').each(function(e) {

                let slider = $(this),
                    width = slider.width(),
                    handle,
                    handleObj;

                let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('viewBox', '0 0 ' + width + ' 83');

                slider.html(svg);
                slider.append($('<div>').addClass('active').html(svg.cloneNode(true)));

                slider.slider({
                    range: true,
                    values: [{{ $categoryPricesFrom }}, {{ $categoryPricesTo }}],
                    min: {{ $categoryPricesMin }},
                    step: 1000,
                    minRange: 10000,
                    max: {{ $categoryPricesMax }},
                    create(event, ui) {

                        slider.find('.ui-slider-handle').append($('<div />'));

                        $(slider.data('value-0')).html(slider.slider('values', 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '&thinsp;'));
                        $(slider.data('value-1')).html(slider.slider('values', 1).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '&thinsp;'));
                        $(slider.data('range')).html((slider.slider('values', 1) - slider.slider('values', 0)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '&thinsp;'));

                        priceRangeSetCSSVars(slider);

                    },
                    start(event, ui) {

                        $('body').addClass('ui-slider-active');

                        handle = $(ui.handle).data('index', ui.handleIndex);
                        handleObj = slider.find('.ui-slider-handle');

                    },
                    change(event, ui) {
                        priceRangeSetCSSVars(slider);
                    },
                    slide(event, ui) {

                        let min = slider.slider('option', 'min'),
                            minRange = slider.slider('option', 'minRange'),
                            max = slider.slider('option', 'max');

                        if(ui.handleIndex == 0) {
                            if((ui.values[0] + minRange) >= ui.values[1]) {
                                slider.slider('values', 1, ui.values[0] + minRange);
                            }
                            if(ui.values[0] > max - minRange) {
                                return false;
                            }
                        } else if(ui.handleIndex == 1) {
                            if((ui.values[1] - minRange) <= ui.values[0]) {
                                slider.slider('values', 0, ui.values[1] - minRange);
                            }
                            if(ui.values[1] < min + minRange) {
                                return false;
                            }
                        }

                        $(slider.data('value-0')).html(ui.values[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '&thinsp;'));
                        $(slider.data('value-1')).html(ui.values[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '&thinsp;'));
                        $(slider.data('range')).html((slider.slider('values', 1) - slider.slider('values', 0)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '&thinsp;'));

                        priceRangeSetCSSVars(slider);

                    },
                    stop(event, ui) {
                        $(slider.data('value-0') + '-input').val(ui.values[0]);
                        $(slider.data('value-1') + '-input').val(ui.values[1]);

                        $('body').removeClass('ui-slider-active');

                        let duration = .6,
                            ease = Elastic.easeOut.config(1.08, .44);

                        TweenMax.to(handle, duration, {
                            '--y': 0,
                            ease: ease
                        });

                        TweenMax.to(svgPath, duration, {
                            y: 42,
                            ease: ease
                        });

                        handle = null;

                    }
                });

                var svgPath = new Proxy({
                    x: null,
                    y: null,
                    b: null,
                    a: null
                }, {
                    set(target, key, value) {
                        target[key] = value;
                        if(target.x !== null && target.y !== null && target.b !== null && target.a !== null) {
                            slider.find('svg').html(priceRangeGetPath([target.x, target.y], target.b, target.a, width));
                        }
                        return true;
                    },
                    get(target, key) {
                        return target[key];
                    }
                });

                svgPath.x = width / 2;
                svgPath.y = 42;
                svgPath.b = 0;
                svgPath.a = width;

                $(document).on('mousemove touchmove', e => {
                    if(handle) {

                        let laziness = 4,
                            max = 24,
                            edge = 52,
                            other = handleObj.eq(handle.data('index') == 0 ? 1 : 0),
                            currentLeft = handle.position().left,
                            otherLeft = other.position().left,
                            handleWidth = handle.outerWidth(),
                            handleHalf = handleWidth / 2,
                            y = e.pageY - handle.offset().top - handle.outerHeight() / 2,
                            moveY = (y - laziness >= 0) ? y - laziness : (y + laziness <= 0) ? y + laziness : 0,
                            modify = 1;

                        moveY = (moveY > max) ? max : (moveY < -max) ? -max : moveY;
                        modify = handle.data('index') == 0 ? ((currentLeft + handleHalf <= edge ? (currentLeft + handleHalf) / edge : 1) * (otherLeft - currentLeft - handleWidth <= edge ? (otherLeft - currentLeft - handleWidth) / edge : 1)) : ((currentLeft - (otherLeft + handleHalf * 2) <= edge ? (currentLeft - (otherLeft + handleWidth)) / edge : 1) * (slider.outerWidth() - (currentLeft + handleHalf) <= edge ? (slider.outerWidth() - (currentLeft + handleHalf)) / edge : 1));
                        modify = modify > 1 ? 1 : modify < 0 ? 0 : modify;

                        if(handle.data('index') == 0) {
                            svgPath.b = currentLeft / 2  * modify;
                            svgPath.a = otherLeft;
                        } else {
                            svgPath.b = otherLeft + handleHalf;
                            svgPath.a = (slider.outerWidth() - currentLeft) / 2 + currentLeft + handleHalf + ((slider.outerWidth() - currentLeft) / 2) * (1 - modify);
                        }

                        svgPath.x = currentLeft + handleHalf;
                        svgPath.y = moveY * modify + 42;

                        handle.css('--y', moveY * modify);

                    }
                });

            });

            function priceRangeGetPoint(point, i, a, smoothing) {
                let cp = (current, previous, next, reverse) => {
                        let p = previous || current,
                            n = next || current,
                            o = {
                                length: Math.sqrt(Math.pow(n[0] - p[0], 2) + Math.pow(n[1] - p[1], 2)),
                                angle: Math.atan2(n[1] - p[1], n[0] - p[0])
                            },
                            angle = o.angle + (reverse ? Math.PI : 0),
                            length = o.length * smoothing;
                        return [current[0] + Math.cos(angle) * length, current[1] + Math.sin(angle) * length];
                    },
                    cps = cp(a[i - 1], a[i - 2], point, false),
                    cpe = cp(point, a[i - 1], a[i + 1], true);
                return `C ${cps[0]},${cps[1]} ${cpe[0]},${cpe[1]} ${point[0]},${point[1]}`;
            }

            function priceRangeGetPath(update, before, after, width) {
                let smoothing = .16,
                    points = [
                        [0, 42],
                        [before <= 0 ? 0 : before, 42],
                        update,
                        [after >= width ? width : after, 42],
                        [width, 42]
                    ],
                    d = points.reduce((acc, point, i, a) => i === 0 ? `M ${point[0]},${point[1]}` : `${acc} ${priceRangeGetPoint(point, i, a, smoothing)}`, '');
                return `<path d="${d}" />`;
            }

            function priceRangeSetCSSVars(slider) {
                let handle = slider.find('.ui-slider-handle');
                slider.css({
                    '--l': handle.eq(0).position().left + handle.eq(0).outerWidth() / 2,
                    '--r': slider.outerWidth() - (handle.eq(1).position().left + handle.eq(1).outerWidth() / 2)
                });
            }
            // end price range

        }); // ready end
    </script>
@endsection
