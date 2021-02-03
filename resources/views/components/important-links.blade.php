<div {{ $attributes->merge(['class' => 'sidebar-box']) }}>
    <div class="standard-side-box mb-5">
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('contacts') }}" class="d-block py-3 font-weight-bold black-link">
                    {{ __('main.our_contacts') }}
                </a>
            </li>
            <li>
                <a href="{{ route('appeals') }}" class="d-block py-3 font-weight-bold black-link">
                    {{ __('main.feedback_title') }}
                </a>
            </li>
            @foreach($pages as $value)
                <li>
                    <a href="{{ $value->url }}" class="d-block py-3 font-weight-bold black-link">
                        {{ $value->short_name_text }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

</div>
