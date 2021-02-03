<div class="home-banner-middle" style="background-image: url({{ $banner->img }})">
    @if($banner->url) <a href="{{ $banner->url }}"> @endif
        <div class="home-banner-middle-content">
            @if ($banner->description_top)
                <h5>{{ $banner->description_top }}</h5>
            @endif
            @if ($banner->description)
                <p>{{ $banner->description }}</p>
            @endif
            @if ($banner->button_text && $banner->url)
                <div class="home-banner-middle-buttons">
                    <span class="btn btn-lg btn-outline-light"><strong>{{ $banner->button_text }}</strong></span>
                </div>
            @endif
        </div>
    @if($banner->url) </a> @endif
</div>
