<?php

use Faker\Generator as Faker;

$factory->define(App\CustomListsEmails::class, function (Faker $faker) {
    return [
        'email' => $faker->email
    ];
});
