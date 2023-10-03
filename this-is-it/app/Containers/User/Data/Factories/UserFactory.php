<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

$factory->define(App\Containers\User\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ? : $password = Hash::make('testing-password'),
        'remember_token' => Str::random(10),
        'is_client'      => false,
    ];
});

$factory->state(App\Containers\User\Models\User::class, 'client', function (Faker\Generator $faker) {
    return [
        'is_client' => true,
    ];
});

$factory->afterCreating(App\Containers\User\Models\User::class, function ($user, $faker) {
  $user->ownProducts()->save(factory(App\Containers\Product\Models\Product::class)->make());
});
