<div class="home-latest-products-slider-container standard-slider-container">
    <div class="home-latest-products-slider standard-slider">
        @foreach($products as $product)
        <div class="home-latest-products-slide">
            @include('partials.product_one')
        </div>
        @endforeach
    </div>
    <span class="standard-slider-arrow standard-slider-arrow-prev">
        <i class="fa fa-angle-left"></i>
    </span>
    <span class="standard-slider-arrow standard-slider-arrow-next">
        <i class="fa fa-angle-right"></i>
    </span>
</div>
