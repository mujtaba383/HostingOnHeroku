<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    $title = $faker->name;
    return [
        'category_id' => $faker->numberBetween($min = 1, $max = 50),
        'author_id' => $faker->numberBetween($min = 1, $max = 50),
        'title' => $title,
        'slug' => Str::slug($title, '-'),
        'availability' => $faker->randomElement(['stock', 'out of stock']),
        'price' => $faker->numberBetween($min = 1000, $max = 10000),
        'rating' => 'rating',
        'publisher' => $faker->company,
        'country_of_publisher' => $faker->country,
        'isbn' => $faker->isbn13,
        'isbn-10' => $faker->isbn10,
        'audience' => $faker->randomElement(['General', 'Adult', 'Kids']),
        'format' => $faker->randomElement(['ePUB', 'eBook', 'PDF', 'DOC']),
        'language' => $faker->languageCode,
        'description' => $faker->text($maxNbChars = 400),
        'book_upload' => 'No pdf found',
        'book_img' => 'No image found',
        'total_pages' => $faker->numberBetween($min = 100, $max = 1000),
        'downloaded' => $faker->numberBetween($min = 1, $max = 1000),
        'edition_number' => $faker->randomElement(['1st Edition', '2nd Edition', '3rd Edition']),
        'recommended' => $faker->boolean,
        'status' => $faker->randomElement(['ACTIVE', 'DEACTIVE', 'UPCOMING'])
    ];
});