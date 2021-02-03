<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Role;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $genders = ['male', 'female'];
    $gender = $genders[array_rand($genders)];
    $role = Role::where('name', 'user')->first();
    return [
        'name' => $faker->name($gender),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'phone_number' => $faker->e164PhoneNumber,
        'phone_number_verified_at' => now(),
        'address' => $faker->streetAddress,
        'role_id' => $role->id,
        'avatar' => 'users/default.png',
    ];
});
