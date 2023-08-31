<?php

use App\Containers\Authorization\Models\Permission;

$factory->define(Permission::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->slug,
        'description' => $faker->sentence,
    ];
});

$factory->define(Spatie\Permission\Models\Permission::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->slug,
        'description' => $faker->sentence,
    ];
});

// ...
