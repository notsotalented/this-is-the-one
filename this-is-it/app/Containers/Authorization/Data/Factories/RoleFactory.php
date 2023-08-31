<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

$factory->define(App\Containers\Authorization\Models\Role::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->slug,
        'level' => $faker->randomDigit,
        'description' => $faker->text,
    ];
});

$factory->define(Spatie\Permission\Models\Role::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->slug,
        'level' => $faker->randomDigit,
        'description' => $faker->text,
    ];
});

// ...
