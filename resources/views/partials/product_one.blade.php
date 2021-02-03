@php
    if (!isset($size)) {
        $size = 'small';
    }
    $showImg = $size . '_img';
@endphp
<div class="product-one product-one-{{ $size }}">
    <div class="product-one-img">
        <a href="{{ $product->url }}">
            <img src="{{ $product->$showImg }}" alt="{{ $product->name }}" class="img-fluid">
        </a>
        {{-- <span class="sticker sticker-wishlist">
            @if(!app('wishlist')->get($product->id))
                <a href="{{ route('wishlist.delete', $product->id) }}"
                   class="add-to-wishlist-btn only-icon" data-id="{{ $product->id }}"
                   data-name="{{ $product->name }}"
                   data-price="{{ $product->current_price }}"
                   data-add-text="<i class='far fa-heart'></i>"
                   data-delete-text="<i class='fas fa-heart'></i>"
                >
                <i class='far fa-heart'></i>
            </a>
            @else
                <a href="{{ route('wishlist.delete', $product->id) }}"
                   class="remove-from-wishlist-btn only-icon" data-id="{{ $product->id }}"
                   data-name="{{ $product->name }}"
                   data-price="{{ $product->current_price }}"
                   data-add-text="<i class='far fa-heart'></i>"
                   data-delete-text="<i class='fas fa-heart'></i>"
                >
                <i class='far fa-heart'></i>
            </a>
            @endif
        </span> --}}
    </div>
    <div class="product-one-content">
        <h4 class="product-one-title"><a href="{{ $product->url }}">{{ $product->name }}</a></h4>
        {{-- <div class="my-2">
            @include('partials.stars', ['rating' => $product->rating_avg])
        </div> --}}

        <div class="product-one-price">
            @if($product->getModel()->isDiscounted())
                <span class="old-price mr-1">
                    {{ Helper::formatPrice($product->price) }}
                </span>
            @endif
            <span class="current-price @if($product->getModel()->isDiscounted()) special-price @endif">
                {{ Helper::formatPrice($product->current_price) }}
            </span>
        </div>
    </div>
</div>
