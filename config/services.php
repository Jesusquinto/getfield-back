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
    'google' => [
        'client_id' => env('938990935440-isfmhu1s5mii7g8q893ihvu9broo1h8o.apps.googleusercontent.com'),
        'client_secret' => env('nf89mNcLForptVzb7uoQjGAp'),
        'redirect' => env('APP_URL').'/auth/adwords/callback'
    ],
];