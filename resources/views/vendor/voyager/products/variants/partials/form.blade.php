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
    <h4>Выберите атрибуты</h4>
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

    <h4>Инфо</h4>

    <div class="form-group">
        <label for="product_variant_sku">Артикул</label>
        <input type="text" name="sku" id="product_variant_sku" class="form-control" value="{{ old('sku', optional($variant)->sku) }}">
    </div>

    <div class="form-group">
        <label for="price">Цена</label>
        <input type="text" name="price" id="price" class="form-control" value="{{ old('price', optional($variant)->price) }}">
    </div>

    <div class="form-group">
        <label for="sale_price">Цена со скидкой</label>
        <input type="text" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', optional($variant)->sale_price) }}">
    </div>

    <div class="form-group" >
        <label class="control-label" for="in_stock">В наличии</label>
        <br>
        {{-- <input type="checkbox" name="in_stock" id="in_stock" @if(old('in_stock', optional($variant)->in_stock)) checked @endif class="toggleswitch" data-on="Да" data-off="Нет"> --}}
        <input type="number" name="in_stock" id="in_stock" class="form-control" value="{{ old('in_stock', optional($variant)->in_stock) }}">
    </div>

    <div class="form-group" >
        <label class="control-label" for="status">Вариант активен</label>
        <br>
        <input type="checkbox" name="status" id="status" @if(old('status', optional($variant)->status)) checked @endif class="toggleswitch" data-on="Да" data-off="Нет">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>

</div>
