<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sslcommerz' => [
        'store_id' => env('SSLCZ_STORE_ID'),
        'store_password' => env('SSLCZ_STORE_PASSWORD'),
        'testmode' => env('SSLCZ_TESTMODE', true),
        'sandbox_url' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
        'live_url' => 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
        'sandbox_validation_url' => 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php',
        'live_validation_url' => 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
    ],

];
