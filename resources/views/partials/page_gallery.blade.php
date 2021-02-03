<div class="mb-4 page-gallery">
    <div class="gallery-full-slider">
        @foreach($page->imgs as $key => $img)
            <div class="gallery-full-slide">
                <a href="{{ $img }}" data-fancybox="gallery">
                    <img src="{{ $img }}" class="img-fluid" alt="{{ $page->name . ' ' . $key }}">
                </a>
            </div>
        @endforeach
    </div>
    <div class="gallery-previews-slider">
        @foreach($page->small_imgs as $key => $small_img)
            <div class="gallery-previews-slide">
                <a href="{{ $page->imgs[$key] }}" data-fancybox="gallery-small">
                    <img src="{{ $small_img }}" class="img-fluid" alt="{{ $page->name . ' ' . $key }}">
                </a>
            </div>
        @endforeach
    </div>
</div>
