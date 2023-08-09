<?php

use Faker\Generator as Faker;

$factory->define(App\TemplateLanguages::class, function (Faker $faker) {
    $languages = collect(['English', 'French', 'Russian', 'German', 'Japanese']);
    $rand_language = $languages->random();

    return [
        'english_name' => $rand_language . '_1',
        'native_name' => $rand_language
    ];
});
