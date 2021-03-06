<?php

/** @var Factory $factory */

use App\Models\Genre;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Genre::class, function (Faker $faker) {
    return [
        'name' =>  $faker->city,
        'description' =>  rand(1,2) % 2 == 0 ? $faker->sentence() : null
    ];
});
