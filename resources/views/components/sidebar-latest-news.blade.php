<div {{ $attributes->merge(['class' => 'sidebar-box']) }}>
    <div class="latest-news-box">
        <div class="news-list">
            @foreach($news as $publication)
                @include('partials.news_list_sm_one')
            @endforeach
        </div>
        <div>
            <a href="{{ route('news') }}" class="btn btn-light btn-rounded">{{ __('main.all_news') }}</a>
        </div>
    </div>
</div>
