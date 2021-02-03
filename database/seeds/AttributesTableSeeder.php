<?php

use App\Attribute;
use Illuminate\Database\Seeder;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Size
        factory(Attribute::class)->create([
            'name' => 'Размер',
            'slug' => 'size',
            'used_for_filter' => 1,
            'type' => Attribute::TYPE_BUTTONS,
        ]);

        // Color
        factory(Attribute::class)->create([
            'name' => 'Цвет',
            'slug' => 'color',
            'used_for_filter' => 1,
            'type' => Attribute::TYPE_COLORS,
        ]);

        // Age
        // factory(Attribute::class)->create([
        //     'name' => 'Возраст',
        //     'name' => 'age',
        //     'used_for_filter' => 0,
        //     'type' => Attribute::TYPE_LIST,
        // ]);

        // Composition
        factory(Attribute::class)->create([
            'name' => 'Состав',
            'slug' => 'material',
            'used_for_filter' => 0,
            'type' => Attribute::TYPE_LIST,
        ]);

        // Gender
        factory(Attribute::class)->create([
            'name' => 'Пол',
            'slug' => 'gender',
            'used_for_filter' => 0,
            'type' => Attribute::TYPE_LIST,
        ]);

        // Collection
        factory(Attribute::class)->create([
            'name' => 'Коллекция',
            'slug' => 'collection',
            'used_for_filter' => 0,
            'type' => Attribute::TYPE_LIST,
        ]);

        // Season
        factory(Attribute::class)->create([
            'name' => 'Сезон',
            'slug' => 'season',
            'used_for_filter' => 0,
            'type' => Attribute::TYPE_LIST,
        ]);

        // Test
        // factory(Attribute::class)->create([
        //     'name' => 'Test',
        //     'used_for_filter' => 1,
        //     'type' => Attribute::TYPE_LIST,
        // ]);

        // Random
        //factory(Attribute::class, 3)->create();
    }
}
