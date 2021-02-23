<?php

use App\AttributeValue;
use App\Category;
use App\Product;
use App\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // simple products
        factory(Product::class, 100)->create([
            'name' => 'Мирабелл F1',
            'description' => 'Рекомендуется для выращивания в стеклянных и пленочных теплицах',
            'body' => '
            <br>
            <div class="table-responsive">
            <table class="table table-striped">
            <tr>
            <th>Элементы удобрения</th>
            <th>% по массе</th>
            </tr>
            <tr>
            <td>Всего азота (N)</td>
            <td>15,5</td>
            </tr>
            <tr>
            <td>Нитратный азот (NO3)</td>
            <td>14,4</td>
            </tr>
            <tr>
            <td>Аммонийный азот (NH4)</td>
            <td>1,1</td>
            </tr>
            <tr>
            <td>Водорастворимый оксид кальция (CaO)</td>
            <td>26,3</td>
            </tr>
            </table>
            </div>

            <h4>Обязанности нитратного азота</h4>
            <p>Это влияет на быстрое и видимое развитие растения. Он не является летучим и не связывается с частицами почвы, поэтому подходит для растений в любое время. Как правило, это предпочтительный источник азота для всех садовых растений и важных сельскохозяйственных растений. Это способствует поглощению растением таких катионов, как калий, кальций и магний.</p>

            <h4>Преимущества</h4>
            <ul>
            <li>Предотвращает дефицит калия в посевах и повышает устойчивость растений к болезням.</li>
            <li>Предотвращает жжение и скручивание листьев внутрь</li>
            <li>Предотвращает измельчение фруктов</li>
            <li>Увеличивает устойчивость растений к жаре и холоду</li>
            <li>Увеличивает производительность за счет увеличения фруктов</li>
            </ul>',
        ])->each(function($product){

            // // attributes
            // $product->attributes()->sync([1, 2]);

            // // attribute values
            // $attributeValueIds = AttributeValue::all()->pluck('id')->toArray();
            // shuffle($attributeValueIds);
            // $product->attributeValues()->sync([$attributeValueIds[0], $attributeValueIds[1]]);

            // categories
            $category = Category::inRandomOrder()->first();
            $product->categories()->sync([$category->id]);
        });

        // // products with variants
        // factory(Product::class, 5)->create()->each(function($product){

        //     // attributes
        //     $product->attributes()->sync([1 => ['used_for_variations' => 1], 2 => ['used_for_variations' => 1]]);
        //     $product->attributes()->syncWithoutDetaching([3, 4]);

        //     // attribute values
        //     $attribute1ValueIds = AttributeValue::where('attribute_id', 1)->get()->pluck('id')->toArray();
        //     $attribute2ValueIds = AttributeValue::where('attribute_id', 2)->get()->pluck('id')->toArray();
        //     $attribute3ValueIds = AttributeValue::where('attribute_id', 3)->get()->pluck('id')->toArray();
        //     $attribute4ValueIds = AttributeValue::where('attribute_id', 4)->get()->pluck('id')->toArray();
        //     shuffle($attribute1ValueIds);
        //     shuffle($attribute2ValueIds);
        //     shuffle($attribute3ValueIds);
        //     shuffle($attribute4ValueIds);

        //     // size, color
        //     $product->attributeValues()->sync([$attribute1ValueIds[0], $attribute1ValueIds[1], $attribute1ValueIds[2], $attribute2ValueIds[0], $attribute2ValueIds[1], $attribute2ValueIds[2]]);

        //     // age, composition
        //     $product->attributeValues()->syncWithoutDetaching([$attribute3ValueIds[0], $attribute4ValueIds[0]]);

        //     // categories
        //     $category = Category::inRandomOrder()->first();
        //     $product->categories()->sync([$category->id]);

        //     // variants

        //     $attribute1Value0 = AttributeValue::find($attribute1ValueIds[0]);
        //     $attribute1Value1 = AttributeValue::find($attribute1ValueIds[1]);
        //     $attribute2Value0 = AttributeValue::find($attribute2ValueIds[0]);
        //     $attribute2Value1 = AttributeValue::find($attribute2ValueIds[1]);
        //     $attribute2Value2 = AttributeValue::find($attribute2ValueIds[2]);

        //     // variant 1
        //     ProductVariant::create([
        //         'product_id' => $product->id,
        //         'combination' => json_encode([$attribute1Value0->id, $attribute2Value0->id]),
        //         'name' => $product->name . ' | ' . $attribute1Value0->name . ', ' . $attribute2Value0->name,
        //         'price' => 500000,
        //         'sku' => $product->id . '-' . $attribute1Value0->id . '-' . $attribute2Value0->id,
        //         'in_stock' => 2,
        //         'status' => 1,
        //     ]);

        //     // variant 2
        //     ProductVariant::create([
        //         'product_id' => $product->id,
        //         'combination' => json_encode([$attribute1Value0->id, $attribute2Value1->id]),
        //         'name' => $product->name . ' | ' . $attribute1Value0->name . ', ' . $attribute2Value1->name,
        //         'price' => 450000,
        //         'sku' => $product->id . '-' . $attribute1Value0->id . '-' . $attribute2Value1->id,
        //         'in_stock' => 3,
        //         'status' => 1,
        //     ]);

        //     // variant 3
        //     ProductVariant::create([
        //         'product_id' => $product->id,
        //         'combination' => json_encode([$attribute1Value1->id, $attribute2Value2->id]),
        //         'name' => $product->name . ' | ' . $attribute1Value1->name . ', ' . $attribute2Value2->name,
        //         'price' => 550000,
        //         'sku' => $product->id . '-' . $attribute1Value1->id . '-' . $attribute2Value2->id,
        //         'in_stock' => 4,
        //         'status' => 1,
        //     ]);

        // });

    }
}
