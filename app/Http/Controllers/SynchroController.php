<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeValue;
use App\Category;
use App\Helpers\Helper;
use App\Product;
use App\ProductVariant;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SynchroController extends Controller
{
    public function torgsoft()
    {
        ini_set('max_execution_time', 600);

        //$this->table();exit;
        $file = storage_path('app/torgsoft/TSGoods.trs');
        //$file = storage_path('app/torgsoft/test.txt');
        if (($handle = fopen($file, 'r')) !== false) {

            $attributeSlugs = [];
            $attributesRaw = [
                'size' => ['name' => 'Размер', 'used_for_filter' => 1],
                'color' => ['name' => 'Цвет', 'used_for_filter' => 1],
                'material',
                // 'gender',
                // 'collection',
                'season',
            ];
            foreach ($attributesRaw as $key => $value) {
                if (is_int($key) && is_string($value)) {
                    $attributeSlugs[] = $value;
                } else {
                    $attributeSlugs[] = $key;
                }
            }
            $attributes = Attribute::whereIn('slug', $attributeSlugs)->with('attributeValues')->get()->keyBy('slug');
            foreach ($attributeSlugs as $key => $value) {
                if (is_int($key) && is_string($value)) {
                    $key = $value;
                    $value = [];
                }
                if (!isset($attributes[$key])) {
                    $attrData = array_merge([
                        'name' => Str::upper($key),
                        'slug' => $key,
                        'used_for_filter' => 0,
                    ], $value);
                    $attributes[$key] = Attribute::create($attrData);
                }
            }

            $row = 0;
            while (($data = fgetcsv($handle, 0, ";")) !== false) {
                $row++;
                if ($row == 1) { // title row
                    continue;
                }

                $torgsoft_id = $data[0];
                $good_name = $data[1] ? trim($data[1]) : '';
                $description = $data[2] ? trim($data[2]) : '';
                $country = $data[3] ? trim($data[3]) : '';
                $sku = $data[4] ? trim($data[4]) : '';
                $price = $data[5] ? (float)$data[5] : 0;
                $sale_price = $data[7] ? (float)$data[7] : 0;
                $sale_price = $sale_price < $price ? $sale_price : 0;
                $quantity = $data[12] ? (int)$data[12] : 0;
                $active = $data[13] ? (int)$data[13] : ProductVariant::STATUS_INACTIVE;
                $size = $data[14] ? trim($data[14]) : '';
                $color = $data[15] ? trim($data[15]) : '';
                $material = $data[16] ? trim($data[16]) : '';
                $fashion_name = $data[17] ? trim($data[17]) : '';
                $gender = $data[18] ? trim($data[18]) : '';
                $short_name = $data[19] ? trim($data[19]) : '';
                $good_type_full = $data[20] ? trim($data[20]) : '';
                $producer_collection_full = $data[21] ? trim($data[21]) : '';
                $season = $data[22] ? trim($data[22]) : '';
                $barcode = $data[23] ? trim($data[23]) : '';

                // all products should have
                if (!$good_name || !$description || !$sku || !$size || !$gender || !$good_type_full || !$barcode) {
                    continue;
                }

                $getName = explode('|', $good_name);
                $name = trim($getName[0]) . ($description ? ' ' . $description : '');

                // find product variant
                $productVariant = ProductVariant::where('barcode', $barcode)->first();
                if ($productVariant) {
                    // product variant found update only price, quantity, status
                    $productVariant->in_stock = $quantity;
                    $productVariant->price = $price;
                    $productVariant->sale_price = $sale_price;
                    $productVariant->status = $price == 0 ? ProductVariant::STATUS_INACTIVE : (int)$active;
                    $productVariant->save();
                } else {
                    // product variant not found

                    // check product
                    $product = Product::where('sku', $sku)->first();

                    // if product not found, create it
                    if (!$product) {

                        $product = Product::create([
                            'name' => $name,
                            'slug' => Str::slug($name),
                            'price' => $price,
                            'sale_price' => $sale_price,
                            'status' => Product::STATUS_PENDING,
                            'in_stock' => 0,
                            'sku' => $sku,
                        ]);

                        if ($product) {

                            // save attributes
                            $product->attributes()->attach($attributes);
                            $product->attributes()->syncWithoutDetaching([$attributes['size']->id => ['used_for_variations' => 1]]);

                            // save categories
                            $parentCategory = Category::where('gender_id', $gender)->first();
                            $categoryNames = explode(',', $good_type_full);
                            $categoryNames = array_map(function($value) {
                                return trim($value);
                            }, $categoryNames);
                            $categoryParentID = null;
                            foreach($categoryNames as $categoryName) {
                                $category = Category::firstOrCreate([
                                    'name' => $categoryName,
                                    'gender_id' => $gender,
                                ], [
                                    'status' => Category::STATUS_ACTIVE,
                                    'slug' => Str::slug($categoryName),
                                    'parent_id' => $parentCategory->id ?? $categoryParentID,
                                    'show_in' => Category::SHOW_IN_MENU,
                                ]);
                                $categoryParentID = $category->id;
                            }
                            $categories = Category::where('gender_id', $gender)->where(function($query) use ($categoryNames) {
                                $query->whereIn('name', $categoryNames)
                                      ->orWhereNull('parent_id');
                            })->get();
                            $product->categories()->attach($categories);
                        } else {
                            // some error
                            continue;
                        }
                    }

                    // save size attribute
                    $attributeValueSize = $attributes['size']->attributeValues()->where('name', $size)->first();
                    if (!$attributeValueSize) {
                        $attributeValueSize = AttributeValue::create([
                            'name' => $size,
                            'attribute_id' => $attributes['size']->id,
                        ]);
                    }
                    $product->attributeValues()->syncWithoutDetaching($attributeValueSize);

                    // create product variant
                    $productVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'external_id' => $torgsoft_id,
                        'name' => $name . ' | ' . $size,
                        'price' => $price,
                        'sale_price' => $sale_price,
                        'status' => $price == 0 ? ProductVariant::STATUS_INACTIVE : (int)$active,
                        'in_stock' => $quantity,
                        'combination' => json_encode([$attributeValueSize->id]),
                        'sku' => $sku,
                        'barcode' => $barcode,
                    ]);

                    // save color attribute
                    $attributeValueColor = $attributes['color']->attributeValues()->where('name', $color)->first();
                    if (!$attributeValueColor) {
                        $attributeValueColor = AttributeValue::create([
                            'name' => $color,
                            'attribute_id' => $attributes['color']->id,
                        ]);
                    }
                    $product->attributeValues()->syncWithoutDetaching($attributeValueColor);

                    // save material attribute
                    $attributeValueMaterial = $attributes['material']->attributeValues()->where('name', $material)->first();
                    if (!$attributeValueMaterial) {
                        $attributeValueMaterial = AttributeValue::create([
                            'name' => $material,
                            'attribute_id' => $attributes['material']->id,
                        ]);
                    }
                    $product->attributeValues()->syncWithoutDetaching($attributeValueMaterial);

                    // save season attribute
                    $attributeValueSeason = $attributes['season']->attributeValues()->where('name', $season)->first();
                    if (!$attributeValueSeason) {
                        $attributeValueSeason = AttributeValue::create([
                            'name' => $season,
                            'attribute_id' => $attributes['season']->id,
                        ]);
                    }
                    $product->attributeValues()->syncWithoutDetaching($attributeValueSeason);
                }
            }
            fclose($handle);
        }
    }

    private function table()
    {
        $file = storage_path('app/torgsoft/TSGoods.trs');
        //$file = storage_path('app/torgsoft/test.txt');
        echo '<table border=1 style="border-collapse: collapse;">';
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ";")) !== false) {
                echo '<tr><td>';
                echo implode('</td><td>', $data);
                echo '</td></td>';
            }
            fclose($handle);
        }
        echo '</table>';
    }
}
