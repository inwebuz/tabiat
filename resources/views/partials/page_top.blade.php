<section class="page-top section-block" @if(!empty($bg)) style="background-image: url({{ $bg }})" @endif>
    <div class="dark-overlay"></div>
    <div class="container">
        {{-- @include('partials.breadcrumbs') --}}
        <h1 class="page-header">{{ $title ?? $page->title ?? '' }}</h1>
    </div>
</section>
