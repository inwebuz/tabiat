<div class="form-group">
    <label for="form_category_id">{{ __('main.category') }} <span
            class="text-danger">*</span></label>
    <select name="category_id" id="form_category_id" class="selectpicker form-control" data-style="btn-outline-secondary" title="{{ __('main.choose') }}" required>
        @php
            $currentCategoryId = old('category_id') ?? $product->category_id;
        @endphp
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @if($currentCategoryId == $category->id) selected @endif>{{ $category->full_name }}</option>
        @endforeach
    </select>
    @error('category_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="form_name">{{ __('main.title') }} <span
            class="text-danger">*</span></label>
    <input id="form_name" type="text"
           class="form-control @error('name') is-invalid @enderror"
           name="name"
           value="{{ old('name') ?? $product->name }}" required
           >
    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group">
    <label for="form_price">{{ __('main.price') }} <span
            class="text-danger">*</span></label>
    <input id="form_price" type="text"
           class="form-control @error('price') is-invalid @enderror"
           name="price"
           value="{{ old('price') ?? $product->price }}" required
           >
    @error('price')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="form_sale_price">{{ __('main.sale_price') }}</label>
    <input id="form_sale_price" type="text"
           class="form-control @error('sale_price') is-invalid @enderror"
           name="sale_price"
           value="{{ old('sale_price') ?? $product->sale_price }}"
           >
    @error('sale_price')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    @php $inStock = old('in_stock') ?? $product->in_stock; @endphp
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="in_stock" id="form_in_stock" @if($inStock) checked @endif>
        <label class="custom-control-label" for="form_in_stock">{{ __('main.in_stock') }}</label>
    </div>
    @error('in_stock')
        <div class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </div>
    @enderror
</div>

<div class="form-group">
    <label for="form_description">{{ __('main.description') }}</label>
    <textarea id="form_description"
              class="form-control @error('description') is-invalid @enderror"
              name="description"
    >{{ old('description') ?? $product->description }}</textarea>
    @error('description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="form_image">{{ __('main.image') }} <span
            class="text-danger">*</span></label>
    @if($product->image)
        <div class="mb-3">
            <img src="{{ $product->medium_img }}" alt="" class="img-fluid">
        </div>
    @endif
    <div class="mb-3">
        <input id="form_image" type="file"
               class="@error('image') is-invalid @enderror"
               name="image"
        >
    </div>
    @error('image')
    <div class="invalid-feedback d-block" role="alert">
        <strong>{{ $message }}</strong>
    </div>
    @enderror
</div>

<div class="form-group">
    <label for="form_body">{{ __('main.body') }}</label>
    <textarea id="form_body"
              class="form-control @error('body') is-invalid @enderror"
              name="body"
    >{{ old('body') ?? $product->body }}</textarea>
    @error('body')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor/translations/ru.js') }}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#form_body' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable' ]
            } )
            .catch( error => {
                console.error( error );
            } );
    </script>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ __('main.form.to_save') }}
    </button>
</div>

