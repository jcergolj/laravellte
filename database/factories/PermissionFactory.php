<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'group' => strtoupper($faker->word),
        'name' => $faker->word,
    ];
});
