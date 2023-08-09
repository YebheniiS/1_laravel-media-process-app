<?php

use Faker\Generator as Faker;

$factory->define(App\CustomLists::class, function (Faker $faker) {
    return [
        'custom_list_name' => $faker->jobTitle,
    ];
});
