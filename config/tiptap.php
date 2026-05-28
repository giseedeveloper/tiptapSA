<?php

/**
 * TIPTAP_sauth — South Africa defaults only.
 * Tanzania settings belong in the TAPTAP tz project.
 */
return [
    'market' => env('TIPTAP_MARKET', 'za'),

    'currency_symbol' => env('TIPTAP_CURRENCY_SYMBOL', 'R'),

    'currency_code' => env('TIPTAP_CURRENCY_CODE', 'ZAR'),

    'country_code' => env('TIPTAP_COUNTRY_CODE', '27'),

    'payment_gateway' => env('TIPTAP_PAYMENT_GATEWAY', 'PayFast'),

    'phone_local_example' => env('TIPTAP_PHONE_EXAMPLE', '082 123 4567'),

    'phone_international_prefix' => env('TIPTAP_PHONE_PREFIX', '+27'),

    'default_whatsapp_bot_number' => env('TIPTAP_WHATSAPP_BOT_NUMBER', '27821234567'),
];
