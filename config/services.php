<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('AWS_KEY'),
        'secret' => env('AWS_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'restpack' => [
        'ACCESS_TOKEN' => env('RESTPACK_ACCESS_TOKEN')
    ],

    'giphy' => [
      'key' => env('GIPHY_API_KEY'),
      'search' => env('GIPHY_SEARCH_URL'),
      'trending' => env('GIPHY_TRENDING_URL')
    ],
    
    'pexels' => [
      'key' => env('PEXELS_API_KEY'),
      'search' => env('PEXELS_SEARCH_URL'),
      'popular' => env('PEXELS_POPULAR_URL')
    ],

    'pixabay' => [
      'key' => env('PIXABAY_API_KEY'),
      'url' =>  env('PIXABAY_ROOT_URL')
    ]
];
