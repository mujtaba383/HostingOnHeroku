<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'fullname' => $faker->name,
        'designation' => $faker->jobTitle,
        'telephone' => $faker->phoneNumber,
        'mobile' => $faker->e164PhoneNumber,
        'email' => $faker->unique()->email,
        'facebook_id' => $faker->unique()->safeEmail,
        'twitter_id' => $faker->unique()->safeEmail,
        'pinterest_id' => $faker->unique()->safeEmail,
        'profile' => $faker->paragraph,
        'team_img' => 'No image found',
        'status' => $faker->randomElement(['DEACTIVE', 'ACTIVE'])
    ];
});
