<section class="category-area section-padding30">
    <div class="container-fluid">
        <div class="row">
            @foreach($banners as $banner)
                <div class="col-xl-4 col-lg-6">
                    <div class="single-category mb-30">
                        <div class="category-img">
                            <a href="{{ $banner->url }}">
                                <img src="{{ $banner->img }}" alt="{{ $banner->name }}" class="img-fluid">
                                <div class="category-caption">
                                    <h2>{{ $banner->name }}</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
