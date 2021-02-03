<div class="single-shop">
    <div class="shop-img">
        <a href="{{ $shop->url }}">
            <img src="{{ $shop->medium_img }}" alt="{{ $shop->name }}" class="img-fluid">
        </a>
    </div>
    <div class="shop-caption">
        <h4><a href="{{ $shop->url }}">{{ $shop->name }}</a></h4>
    </div>
</div>
