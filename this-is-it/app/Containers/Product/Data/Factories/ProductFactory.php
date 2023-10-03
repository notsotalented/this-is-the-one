<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

$factory->define(App\Containers\Product\Models\Product::class, function (Faker\Generator $faker) {

    return [
      'name' => $faker->name,
      'description' => $faker->text,
      'price' => $faker->randomDigit(),
      'quantity' => $faker->randomDigit(),
      'brand' => $faker->randomElement(['Tamiya ARC', 'HobbyBoss ARC', 'Tamiya LQR', 'HobbyBoss LQR', '私は黒狐です。'])
    ];
});


// ...
