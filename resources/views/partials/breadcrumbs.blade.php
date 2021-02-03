@if(!empty($breadcrumbs))
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach($breadcrumbs->getItems() as $link)
            @if($link->isActive())
            <li class="breadcrumb-item"><a href="{{ $link->url }}">{{ $link->name }}</a></li>
            @else
            <li class="breadcrumb-item active" aria-current="page">{{ $link->name }}</li>
            @endif
            @endforeach
        </ol>
    </nav>
@endif
