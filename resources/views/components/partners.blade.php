<div class="container">
    <div class="partners-swiper">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach ($brands as $key => $brand)
                    <div class="swiper-slide">
                        <div>
                            <a href="{{ $brand->url }}" class="d-block">
                                <img src="{{ $brand->medium_img }}" alt="{{ $brand->name }}" class="img-fluid">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-arrows mt-4">
            <div class="swiper-button-prev">
                <svg width="18" height="18">
                    <use xlink:href="#swiper-arrow"></use>
                </svg>
            </div>
            <div class="swiper-button-next">
                <svg width="18" height="18">
                    <use xlink:href="#swiper-arrow"></use>
                </svg>
            </div>
        </div>
    </div>

</div>

