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
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'paypal' => [
        'sandbox' => [
            'client_id' => 'AX_ye35DfSXAy0WR6facplrU_Ew8zR_LsWJiFCRg6FzypKv5B2yfO_Jo0uWIbuSQHMAE0jysVK0bNgXH',
            'secret' => 'EBrya53N5h664cXLOiBDj1-JEhToMLRJHIo9uW2VYqatTHZv704Q05fptiRJxNeTO9WHlM2BhsUqJhiM',
        ],
        'live' => [
            'client_id' => 'AT62VWGtWohJZD1okpkDhc5w9dggQHKeenT4Xaz9RQNGzmE0pN_VmQhHDRd3w-d4avfS74mfW0PR2wPG',
            'secret' => 'EIlC11xOkWAYAl1MQo7nA7ht_8XbIoLHsnOHLuNuRiegHqdVZg6WGDhEJQgeM4dPpEakeR9z6aVijJib'
        ],
    ],

    'payeezy' => [
        'sandbox' => [
            'key' => 'hV7BVVvjACzDOGl2CgDBFLWNrpeJm01W',
            'secret' => 'ddea9c5cd40e5078d772afbf2607ccda41c3094199aa392cbac7fdc120cc3415',
        ],
        'live' => [
            'key' => 'UbI1DZMH2Zw1IUSaeGP1NMvimcYy7mem',
            'secret' => 'd0fa765907fc81e3f7ccd12f5c386b280848cb0fc9318faa1273b3f97fe9a1fc'
        ],
    ],
];
