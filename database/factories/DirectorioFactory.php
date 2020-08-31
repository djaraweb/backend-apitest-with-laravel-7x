<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Directorio;
use Faker\Generator as Faker;

$factory->define(Directorio::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'direction' => $faker->address,
        'phone' => $faker->tollFreePhoneNumber,
        'urlavatar' => $faker->imageUrl,
    ];
});
