@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel-body">
    <h4>{{ __('main.choose_attributes') }}</h4>
    @foreach($productVariantAttributes as $productVariantAttribute)
        <div class="row">
            <div class="col-sm-4 mb-0">
                <div class="form-group">
                    <label>{{ $productVariantAttribute->name }}</label>
                    <select name="attributes[attribute_{{ $productVariantAttribute->id }}]" id="attributes_attribute_{{ $productVariantAttribute->id }}" class="form-control select2" required>
                        <option value="">Выбрать</option>
                        @foreach($productVariantAttribute->attributeValues as $attributeValue)
                            <option value="{{ $attributeValue->id }}" @if(in_array($attributeValue->id, $variantAttributeValueIds)) selected @endif>{{ $attributeValue->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <label for="price">{{ __('main.price') }}</label>
        <input type="text" name="price" id="price" class="form-control" value="{{ old('price', optional($variant)->price) }}">
    </div>

    <div class="form-group">
        <label for="sale_price">{{ __('main.sale_price') }}</label>
        <input type="text" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', optional($variant)->sale_price) }}">
    </div>

    <div class="form-group" >
        <label class="control-label" for="in_stock">{{ __('main.in_stock') }}</label>
        <br>
        <input type="checkbox" name="in_stock" id="in_stock" @if(old('in_stock', optional($variant)->in_stock)) checked @endif>
    </div>

    <div class="form-group" >
        <label class="control-label" for="status">{{ __('main.variant_is_active') }}</label>
        <br>
        <input type="checkbox" name="status" id="status" @if(old('status', optional($variant)->status)) checked @endif>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('main.form.to_save') }}</button>
    </div>

</div>
