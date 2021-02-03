<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Brand;
use App\Category;
use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    $wordCount = mt_rand(2, 8);
    $title = Str::title(implode(' ', $faker->words($wordCount)));
    // $category = Category::inRandomOrder()->first();
    $price = mt_rand(50, 500) * 1000;
    $imgNumber = mt_rand(1, 4);
    $random10 = mt_rand(0, 9);
    // $brand = Brand::inRandomOrder()->first();


    $product = [
        'name' => $title,
        'slug' => Str::slug($title),
        'description' => $faker->paragraph,
        'body' => '<p>' . implode('</p><p>', $faker->paragraphs(4)) . '</p>',
        'status' => 1,
        'specifications' => '<p>' . implode('</p><p>', $faker->paragraphs(4)) . '</p>',
        // 'category_id' => $category->id,
        //'brand_id' => $brand->id,
        'price' => $price,
        'sale_price' => in_array($random10, [6, 7]) ? $price * 0.9 : 0,
        'image' => 'products/0' . $imgNumber . '.jpg',
        'images' => '["products//0' . $imgNumber . '.jpg","products//0' . $imgNumber . '.jpg","products//0' . $imgNumber . '.jpg","products//0' . $imgNumber . '.jpg"]',
        'in_stock' => mt_rand(0, 9),
        'is_featured' => $random10 == 9 ? 1 : 0,
        'is_new' => $random10 == 8 ? 1 : 0,
        'sku' => $faker->uuid,
    ];

    return $product;
});
