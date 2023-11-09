<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(ReleaseVueJS::class, function (Faker $faker) {
    $image = UploadedFile::fake()->image(rand(1, 1000) . 'release.jpg', 200, 200);
    Storage::disk('public')->putFileAs('images-release', $image, $image->getClientOriginalName());
    return [
        'name' => $faker->unique()->name,
        'title_description' => $faker->text,
        'detail_description' => $faker->text,
        'is_publish' => $faker->boolean,
        'images' => [
            '/images-release/' . $image->getClientOriginalName(),
        ],
    ];
});