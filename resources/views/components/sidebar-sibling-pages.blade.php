@if(!$siblingPages->isEmpty())
    <div {{ $attributes->merge(['class' => 'sidebar-box']) }}>
        <div class="sibling-pages-box mb-5">
            <ul class="list-unstyled">
                @foreach($siblingPages as $siblingPage)
                    <li>
                        <a href="{{ $siblingPage->url }}" class="@if($siblingPage->id == $page->id) active @endif d-block py-2 pl-3 pl-lg-4 font-weight-bold black-link">
                            {{ $siblingPage->short_name_text }}
                        </a>
                        @if ($siblingPage->id == $page->id && !$siblingPage->pages->isEmpty())
                            <ul class="list-unstyled">
                                @foreach($siblingPage->pages as $siblingPagePage)
                                    <li>
                                        <a href="{{ $siblingPagePage->url }}" class="d-block pb-1 pl-4 pl-lg-5 black-link">
                                            <small>- {{ $siblingPagePage->short_name_text }}</small>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
