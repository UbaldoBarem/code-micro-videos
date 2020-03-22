<?php

/** @var Factory $factory */

use App\Models\Video;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Video::class, function (Faker $faker) {

    $rating = Video::RATTING_LIST[array_rand(Video::RATTING_LIST)] ;

    return [
        'title' => $faker->sentence(3),
        'description' => $faker->sentence(10),
        'opened' => rand(0,1),
        'year_launched' => rand(1895,2022),
        'duration' => rand(1,200),
        'rating' => $rating,
        'thumb_file' => null,
        'banner_file' => null,
        'trailer_file' => null,
        'video_file' => null,
    ];
});

/*
        'thumb_file' => null,
        'banner_file' => null,
        'trailer_file' => null,
        'video_file' => null,
        'published' => rand(0,1)
 */
