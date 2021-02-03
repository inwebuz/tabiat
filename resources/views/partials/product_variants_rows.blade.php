<div class="product-page-box">
    <div class="product-variants-block">

        <div class="product-variants">
            @foreach($productVariants as $productVariant)
                <input type="hidden" class="product-variant-item" value="{{ $productVariant->id }}" data-id="{{ $productVariant->id }}" data-name="{{ $productVariant->name }}" data-price="{{ $productVariant->current_price }}" data-formatted-price="{{ Helper::formatPrice($productVariant->current_price) }}" data-base-price="{{ $productVariant->price }}" data-formatted-base-price="{{ Helper::formatPrice($productVariant->price) }}" data-combination="{{ $productVariant->combination_name }}" data-in-stock="{{ $productVariant->in_stock }}">
            @endforeach
        </div>

        @foreach($productVariantsAttributes as $productVariantsAttribute)
            <div class="product-variant-attribute mb-4" data-id="{{ $productVariantsAttribute->id }}">

                <h5 class="small-header">{{ $productVariantsAttribute->name }}</h5>
                @php
                    $productVariantsAttributeValues = $productVariantsAttribute->attributeValues->sortBy('name');
                @endphp
                @if ($productVariantsAttribute->type == \App\Attribute::TYPE_BUTTONS)
                    <div class="product-variant-attributes-row product-variant-attributes-row-buttons mt-2">

                        @foreach($productVariantsAttributeValues as $key => $productVariantsAttributeValue)
                            <span class="product-variant-attribute-value d-inline-block mb-1" data-attribute-id="{{ $productVariantsAttribute->id }}" data-id="{{ $productVariantsAttributeValue->id }}">
                                <button class="btn btn-outline-light" type="button">
                                    {{ $productVariantsAttributeValue->name }}
                                </button>
                            </span>
                        @endforeach
                    </div>
                @elseif ($productVariantsAttribute->type == \App\Attribute::TYPE_COLORS)
                    <div class="product-variant-attributes-row product-variant-attributes-row-colors mt-2">
                        @foreach($productVariantsAttributeValues as $key => $productVariantsAttributeValue)
                            <span class="product-variant-attribute-value d-inline-block mb-1" data-attribute-id="{{ $productVariantsAttribute->id }}" data-id="{{ $productVariantsAttributeValue->id }}">
                                <span class="color-button @if($productVariantsAttributeValue->is_light_color) color-button-light @endif"  style="background-color: {{ $productVariantsAttributeValue->color }}" title="{{ $productVariantsAttributeValue->name }}"></span>
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="product-variant-attributes-row product-variant-attributes-row-list mt-2">
                        @foreach($productVariantsAttributeValues as $productVariantsAttributeValue)
                            <div class="mb-2 product-variant-attribute-value" data-attribute-id="{{ $productVariantsAttribute->id }}" data-id="{{ $productVariantsAttributeValue->id }}">
                                {{ $productVariantsAttributeValue->name }}
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</div>
