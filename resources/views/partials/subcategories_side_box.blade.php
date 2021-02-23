@if(!$subcategories->isEmpty())
    <div class="side-box">
        <h3 class="box-header">{{ __('main.categories') }}</h3>
        <ul class="side-box-list list-unstyled">
            @foreach($subcategories as $subcategory)
                <li>
                    <a href="{{ $subcategory->url }}">{{ $subcategory->name }}</a>
                    @if (!$subcategory->children->isEmpty())
                        <ul class="list-unstyled">
                            @foreach ($subcategory->children->translate() as $item)
                                <li>
                                    <a href="{{ $item->url }}">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
