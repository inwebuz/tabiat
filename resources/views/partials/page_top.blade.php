<div class="page-top" @if(!empty($bg)) style="background-image: url({{ $bg }})" @endif>
    <div class="container">
        @include('partials.breadcrumbs')
        <h1 class="page-header">{{ $title ?? $page->title ?? '' }}</h1>
    </div>
    @include('partials.waves')
</div>
