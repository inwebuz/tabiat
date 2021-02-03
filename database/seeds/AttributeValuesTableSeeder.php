<?php

use App\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Size
        factory(AttributeValue::class)->create([
            'attribute_id' => 1,
            'name' => 'S',
            'used_for_filter' => 1,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 1,
            'name' => 'M',
            'used_for_filter' => 1,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 1,
            'name' => 'L',
            'used_for_filter' => 1,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 1,
            'name' => 'XL',
            'used_for_filter' => 1,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 1,
            'name' => 'XXL',
            'used_for_filter' => 1,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 1,
            'name' => 'XXXL',
            'used_for_filter' => 1,
        ]);

        // Color
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Белый',
            'used_for_filter' => 1,
            'color' => '#ffffff',
            'is_light_color' => 1,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Черный',
            'used_for_filter' => 1,
            'color' => '#000000',
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Красный',
            'used_for_filter' => 1,
            'color' => '#DE1F16',
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Зеленый',
            'used_for_filter' => 1,
            'color' => '#8BC34C',
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Бежевый',
            'used_for_filter' => 1,
            'color' => '#DCCBB8',
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Голубой',
            'used_for_filter' => 1,
            'color' => '#73BFEB',
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 2,
            'name' => 'Горчичный',
            'used_for_filter' => 1,
            'color' => '#9C4C01',
        ]);

        factory(AttributeValue::class)->create([
            'attribute_id' => 3,
            'name' => '6-12 лет',
            'used_for_filter' => 0,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 3,
            'name' => '3-15 лет',
            'used_for_filter' => 0,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 4,
            'name' => 'верх: 100% нейлон / подкладка: 100% полиэстер',
            'used_for_filter' => 0,
        ]);
        factory(AttributeValue::class)->create([
            'attribute_id' => 4,
            'name' => '100% хлопок',
            'used_for_filter' => 0,
        ]);

        // Test
        // factory(AttributeValue::class, 5)->create([
        //     'attribute_id' => 3,
        //     'used_for_filter' => 1,
        // ]);

        // Random
        // factory(AttributeValue::class, 5)->create([
        //     'attribute_id' => 3,
        // ]);
    }
}
