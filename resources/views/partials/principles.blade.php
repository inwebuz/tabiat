<div class="shop-method-area section-padding30">
    <div class="container">
        <div class="row d-flex justify-content-between">
            @foreach ($principles as $principle)
            <div class="col-lg-3">
                <div class="single-method">
                    <div class="mb-2">
                        <img src="{{ $principle->img }}" alt="" class="img-fluid">
                    </div>
                    <h6>{{ $principle->name }}</h6>
                    <p>{{ $principle->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
