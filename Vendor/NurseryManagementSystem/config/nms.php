<?php

return [
    'middleware' => ['web', 'auth'],
    'prefix' => 'nursery',

    'roles' => [
        'admin' => 'Admin',
        'teacher' => 'Teacher',
        'parent' => 'Parent',
    ],

    'notifications' => [
        'sms_driver' => env('NMS_SMS_DRIVER', 'log'),
        'whatsapp_driver' => env('NMS_WHATSAPP_DRIVER', 'log'),
        'email_from' => env('NMS_EMAIL_FROM', null),
    ],

    'payments' => [
        'provider' => env('NMS_PAYMENT_PROVIDER', 'manual'),
        'currency' => env('NMS_CURRENCY', 'USD'),
    ],

    'ui' => [
        'brand_name' => env('NMS_BRAND', 'Nursery Manager'),
        'theme' => [
            'primary' => '#6c9bd2',
            'secondary' => '#f7a6b5',
            'accent' => '#ffd166',
            'success' => '#8fd694',
        ],
    ],
];
