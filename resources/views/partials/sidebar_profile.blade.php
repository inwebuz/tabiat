
<div class="sidebar">

    <div class="sidebar-block">
        <h3 class="sidebar-title">{{ __('main.profile_menu') }}</h3>
        <div class="list-group">
            @foreach($menu as $menuItem)
                <a href="{{ $menuItem->url }}" class="list-group-item">{{ $menuItem->name }}</a>
            @endforeach
        </div>
    </div>

</div>
