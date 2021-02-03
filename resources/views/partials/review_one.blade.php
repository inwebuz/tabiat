<div class="review media">
    <div class="avatar ">
        <img src="{{ ($review->user_id) ? $review->user->avatar_img : asset('images/avatar.jpg') }}" alt="{{ $review->name }}" class="img-fluid">
    </div>
    <div class="media-body">
        <div class="review-rating">
            @include('partials.stars_input', ['rating' => $review->rating])
        </div>
        <div class="review-message">
            {{ $review->body }}
        </div>
        <div class="review-info">
            <span class="btn btn-xs standard-date-btn mr-2">{{ Helper::formatDate($review->created_at) }}</span>
            <span class="review-author">{{ $review->name }}</span>
        </div>
        @can('edit', $review)
            <div class="list-group my-4">
                <a href="{{ route('voyager.reviews.edit', $review->id) }}" target="_blank" class="list-group-item">Редактировать</a>
                <a href="{{ route('voyager.reviews.index', ['custom_filters[ip_address]' => $review->ip_address]) }}"  target="_blank"class="list-group-item">Все отзывы с этого IP ({{ $review->ip_address }})</a>
                <a href="{{ route('voyager.reviews.index', ['custom_filters[reviewable_id]' => $review->reviewable_id, 'custom_filters[reviewable_type]' => $review->reviewable_type]) }}" target="_blank" class="list-group-item">Все отзывы к этой публикации</a>
                <a href="{{ route('voyager.reviews.index', ['custom_filters[user_id]' => $review->user_id]) }}" target="_blank" class="list-group-item">Все отзывы этого пользователя</a>
            </div>
        @endcan
    </div>
</div>
