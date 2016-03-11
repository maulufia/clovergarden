<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => clovergarden\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'facebook' => [
        'client_id' => '192015317838438',
        'client_secret' => '8ba778ae392a18758beaa61ecaf5ba71',
        'redirect' => 'http://clovergarden.co.kr/login/facebook/callback',
    ],
    
    'naver' => [
        'client_id' => 'vFxs5eu9CEF4puGua7IE',
        'client_secret' => '0b5VSdaabW',
        'redirect' => 'http://clovergarden.co.kr/login/naver/callback',
    ],
    
    'kakao' => [
        'client_id' => '74aa869cab3419d7054058079c7dd094',
        'client_secret' => '',
        'redirect' => 'http://clovergarden.co.kr/login/kakao/callback',
    ],

];
