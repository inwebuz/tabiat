<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Brand;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Brand::class, function (Faker $faker) {
    $wordCount = mt_rand(1, 3);
    $title = Str::title(implode(' ', $faker->words($wordCount)));
    return [
        'name' => $title,
        'slug' => Str::slug($title),
        'description' => $faker->paragraph,
        'body' => '<p>' . implode('</p><p>', $faker->paragraphs(4)) . '</p>',
        'status' => 1,
    ];
});
