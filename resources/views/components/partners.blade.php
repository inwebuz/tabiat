<div class="container">
    <div class="row logo-items">
        @foreach ($brands as $key => $brand)
            <div class="col-lg-2 col-sm-4 col-6">
                <img src="{{ $brand->img }}" alt="{{ $brand->name }}" class="img-fluid">
            </div>
        @endforeach
    </div>
</div>
