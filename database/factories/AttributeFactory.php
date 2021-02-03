<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attribute;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Attribute::class, function (Faker $faker) {
    $wordCount = mt_rand(1, 2);
    $title = Str::title(implode(' ', $faker->words($wordCount)));
    return [
        'name' => $title,
        'slug' => Str::slug($title),
        'type' => Attribute::TYPE_LIST,
    ];
});
