<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attribute;
use App\AttributeValue;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(AttributeValue::class, function (Faker $faker) {
    $wordCount = mt_rand(1, 1);
    $title = Str::title(implode(' ', $faker->words($wordCount)));
    $attribute = Attribute::inRandomOrder()->first();
    return [
        'name' => $title,
        'slug' => Str::slug($title),
        'attribute_id' => $attribute->id,
    ];
});
