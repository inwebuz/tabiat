<?php

namespace App\Http\Controllers\Voyager;

use App\Attribute;
use App\AttributeValue;
use App\Product;
use App\ProductVariant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use const JSON_UNESCAPED_UNICODE;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class VoyagerProductController extends VoyagerBaseController
{
    public function attributesEdit(Request $request, Product $product)
    {
        // Check permission
        $this->authorize('edit', $product);

        $attributes = Attribute::all();
        $attributeValueIds = $product->attributeValues()->pluck('attribute_value_id')->toArray();

        return Voyager::view('voyager::products.edit-attributes', compact('product', 'attributes', 'attributeValueIds'));
    }

    public function attributesUpdate(Request $request, Product $product)
    {
        // Check permission
        $this->authorize('edit', $product);

        $attributes = $request->input('attributes', []);

        // product attributes
        $productAttributes = [];
        foreach($attributes as $key => $attribute) {
            $productAttributes[$key] = [
                'used_for_variations' => (isset($attribute['used_for_variations']) && $attribute['used_for_variations']) ? 1 : 0,
            ];
        }
        $product->attributes()->sync($productAttributes);

        // product attribute values
        $productAttributeValues = [];
        foreach($attributes as $attribute) {
            $productAttributeValues = array_merge($productAttributeValues, $attribute['values']);
        }
        $product->attributeValues()->sync($productAttributeValues);

        return redirect()->back()->with([
            'message'    => 'Атрибуты сохранены',
            'alert-type' => 'success',
        ]);
    }

    public function variants(Request $request, Product $product)
    {
        // Check permission
        $this->authorize('edit', $product);

        $variants = $product->productVariants;
        return Voyager::view('voyager::products.variants.browse', compact('product', 'variants'));
    }

    public function variantsCreate(Request $request, Product $product)
    {
        // Check permission
        $this->authorize('edit', $product);

        $productVariantAttributes = $product->variantAttributesWithValues()->get();

        $variant = new ProductVariant();
        $variantAttributeValueIds = [];

        return Voyager::view('voyager::products.variants.create', compact('product', 'variant', 'productVariantAttributes', 'variantAttributeValueIds'));
    }

    public function variantsStore(Request $request, Product $product)
    {
        // Check permission
        $this->authorize('edit', $product);

        return $this->variantsSave($request, $product);
    }

    public function variantsEdit(Request $request, Product $product, ProductVariant $variant)
    {
        // Check permission
        $this->authorize('edit', $product);

        $productVariantAttributes = $product->variantAttributesWithValues()->get();

        $variantAttributeValueIds = $variant->attributeValueIds();

        return Voyager::view('voyager::products.variants.edit', compact('product', 'variant', 'productVariantAttributes', 'variantAttributeValueIds'));
    }

    public function variantsUpdate(Request $request, Product $product, ProductVariant $variant)
    {
        // Check permission
        $this->authorize('edit', $product);

        return $this->variantsSave($request, $product);
    }

    private function variantsSave(Request $request, Product $product)
    {
        // Check permission
        $this->authorize('edit', $product);

        $validation = [
            'price' => 'required|numeric',
        ];
        $productAttributes = $product->variantAttributes()->get();
        $productAttributeValueIds = $product->attributeValuesIds()->toArray();

        foreach($productAttributes as $attribute) {
            $validation['attributes.attribute_' . $attribute->id] = 'required|in:' . implode(',', $productAttributeValueIds);
        }
        $request->validate($validation, [
            '*.required' => 'Обязательное поле',
        ]);

        $dataCheck = [];
        $data = [];

        $attributes = $request->input('attributes', []);
        $attributes = array_map(function($val) {
            return (int)$val;
        }, array_values($attributes));
        sort($attributes);

        $dataCheck['product_id'] = $product->id;
        $dataCheck['combination'] = json_encode($attributes);

        $attributeValueNames = AttributeValue::whereIn('id', $attributes)->pluck('name');
        $data['name'] = $product->name . ' | ' . $attributeValueNames->implode(', ');

        $data['price'] = (float)$request->input('price', 0);
        $data['sale_price'] = (float)$request->input('sale_price', 0);

        $data['sku'] = $request->input('sku', '');

        $data['in_stock'] = (int)$request->input('in_stock', 0);
        //$data['in_stock'] = $request->has('in_stock') ? 1 : 0;

        $data['status'] = $request->has('status') ? 1 : 0;

        // TODO: delete old img and save new img

        ProductVariant::updateOrCreate($dataCheck, $data);

        return redirect()->route('voyager.products.variants', $product->id)->with([
            'message'    => 'Вариант сохранен',
            'alert-type' => 'success',
        ]);
    }

    public function variantsDestroy(Request $request, Product $product, ProductVariant $variant)
    {
        // Check permission
        $this->authorize('edit', $product);

        // TODO: delete img

        $variant->delete();
        return redirect()->back()->with([
            'message'    => 'Вариант удален',
            'alert-type' => 'success',
        ]);
    }
}
