@php
    if (!isset($size)) {
        $size = 'small';
    }
    $showImg = $size . '_img';
@endphp
<div class="catalog-item" title="{{ $product->name }}">
    <div class="catalog-item__img">
        <a href="{{ $product->url }}">
            <img src="{{ $product->$showImg }}" alt="{{ $product->name }}" class="img-fluid">
        </a>
    </div>
    <div class="catalog-item__body text-center">
        <h4><a href="{{ $product->url }}" class="black-link">{{ Str::limit($product->name, 40) }}</a></h4>
        <p>{{ Str::words($product->description, 10) }}</p>
    </div>
</div>
{{-- <div class="product-one-price">
    @if($product->getModel()->isDiscounted())
        <span class="old-price mr-1">
            {{ Helper::formatPrice($product->price) }}
        </span>
    @endif
    <span class="current-price @if($product->getModel()->isDiscounted()) special-price @endif">
        {{ Helper::formatPrice($product->current_price) }}
    </span>
</div> --}}
