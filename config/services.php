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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    '2checkout' => [
        '2CHECKOUT_SELLER_ID' => env('2CHECKOUT_SELLER_ID'),
        '2CHECKOUT_PRIVATE_KEY' => env('2CHECKOUT_PRIVATE_KEY'),
        '2CHECKOUT_PUPLIC_KEY' => env('2CHECKOUT_PUPLIC_KEY'),
        '2CHECKOUT_IS_SANDBOX' => env('2CHECKOUT_IS_SANDBOX'),
        '2CHECKOUT_IS_verifySSL' => env('2CHECKOUT_IS_verifySSL'),
    ],

    'paypalpro' => [
        'PAYPAL_USERNAME'  => env('PAYPAL_USERNAME'),
        'PAYPAL_PASSWORD'  => env('PAYPAL_PASSWORD'),
        'PAYPAL_SIGNATURE'  => env('PAYPAL_SIGNATURE'),
        'PAYPAL_TESTMODE'  => env('PAYPAL_TESTMODE'),
    ],

    'paypal' => [
        'PAYPAL_CLIENT_ID' => env('PAYPAL_CLIENT_ID'),
        'PAYPAL_SECRET' => env('PAYPAL_SECRET'),
        'PAYPAL_MODE' => env('PAYPAL_MODE')
    ],

    'NMIPayments' => [
        'NMI_SECURITY_KEY' => env('NMI_SECURITY_KEY')
    ],

    'authorizeNet' => [
        'MERCHANT_LOGIN_ID' => env('MERCHANT_LOGIN_ID'),
        'MERCHANT_TRANSACTION_KEY' => env('MERCHANT_TRANSACTION_KEY')
    ],

    'TWILIO' => [
        'TWILIO_SID' => env('TWILIO_SID'),
        'TWILIO_AUTH_TOKEN' => env('TWILIO_AUTH_TOKEN'),
        'TWILIO_NUMBER' => env('TWILIO_NUMBER'),
        'TWILIO_NUMBER2' => env('TWILIO_NUMBER2')
    ],

    'ApiLead' => [
        'API_Campaign_ID' => env('API_Campaign_ID'),
        'API_Campaign_Key' => env('API_Campaign_Key')
    ],

    'CALLTOOLS_API_KEY' => env('CALLTOOLS_API_KEY'),


    'Trusted_Form_Auth' => env('Trusted_Form_Auth'),

    'JORNAYA' => [
        'JORNAYA_lac' => env('JORNAYA_lac'),
        'JORNAYA_lak' => env('JORNAYA_lak'),
        'JORNAYA_lpc' => env('JORNAYA_lpc')
    ],

    'RECAPTCHA' => [
        'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY'),
        'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY')
    ],

    'TELESIGN' => [
        'TELESIGN_CUSTOMER_ID' => env('TELESIGN_CUSTOMER_ID'),
        'TELESIGN_KEY' => env('TELESIGN_KEY')
    ],

    'SALES_DASHBOARD' => [
        'SALES_TARGET' => env('SALES_TARGET'),
        'DALY_SALES_MAX_TRANSFER' => env('DALY_SALES_MAX_TRANSFER'),
        'SDR_TARGET' => env('SDR_TARGET'),
        'DALY_SDR_MAX_TRANSFER' => env('DALY_SDR_MAX_TRANSFER'),
        'CALLCENTER_TARGET' => env('CALLCENTER_TARGET'),
        'DALY_CALLCENTER_MAX_TRANSFER' => env('DALY_CALLCENTER_MAX_TRANSFER'),
    ],

    'PAYMENT_METHODS' => env('PAYMENT_METHODS', false),
    'AUTO_PAYMENT_METHODS' => env('AUTO_PAYMENT_METHODS', false),
    'EnableRecording' => env('EnableRecording', false),
];
