<div class="publication-one">
    <a href="{{ $publication->url }}" class="news-item" style="background-image: url('{{ $publication->medium_img }}')">
        <div class="news-one-content">
            <p class="date">{{ Helper::formatDate($publication->getModel()->created_at, true) }}</p>
            <h5>{{ $publication->name }}</h5>
            <span class="btn btn-outline-light">{{ __('main.view_more') }}</span>
        </div>
    </a>
</div>
