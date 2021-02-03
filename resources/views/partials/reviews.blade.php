<div class="p-4" id="page-reviews-block">
    @if(!$reviews->isEmpty())
        <div class="reviews">
            @foreach($reviews as $review)
                @include('partials.review_one')
            @endforeach
        </div>
    @else
        <div class="pb-4">
            {{ __('main.no_reviews') }}
        </div>
    @endif
    <div>
        <button class="btn btn-primary" data-toggle="collapse" data-target="#review-create">
            {{ __('main.write_a_review') }}
        </button>
        <div id="review-create" class="collapse">
            <br>
            <form action="{{ route('reviews.store') }}" method="post" class="review-form">

                @csrf

                <input type="hidden" name="reviewable_id" value="{{ $reviewable_id }}">
                <input type="hidden" name="reviewable_type" value="{{ $reviewable_type }}">

                <div class="form-group">
                    <label for="review_name" >{{ __('main.form.your_name') }}</label>
                    <input type="text" name="name" id="review_name" class="form-control" value="@auth{{ auth()->user()->name }}@endauth" required>
                </div>
                <div class="form-group">
                    <label for="">{{ __('main.mark') }}</label>
                    @include('partials.rating_field')
                </div>
                <div class="form-group">
                    <label for="review_body">{{ __('main.form.message') }}</label>
                    <textarea name="body" id="review_body" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('main.form.send') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
