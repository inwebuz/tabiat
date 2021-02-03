<div class="star-rating disabled">
	@php $random = Str::random(); @endphp
	@for($i = config('cms.rating'); $i >= 1; $i--)
	<input id="star-{{ $i }}-{{ $random }}" type="radio" name="rating-{{ $random }}" value="{{ $i }}" @if($i == $rating) checked @endif>
    <label for="star-{{ $i }}-{{ $random }}" title="">
        <i class="fa fa-star" ></i>
    </label>
	@endfor
</div>
