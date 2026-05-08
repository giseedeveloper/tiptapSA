<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Bot Notification Endpoint
    |--------------------------------------------------------------------------
    |
    | The internal URL of the Node.js (Baileys) bot's notify HTTP server.
    | When server-side events occur (e.g. an order reaches the "served"
    | stage), Laravel pushes a notification here so the bot can deliver
    | the message to the customer over WhatsApp without polling.
    |
    */

    'bot_notify_url' => env('WHATSAPP_BOT_NOTIFY_URL'),

    'bot_notify_secret' => env('WHATSAPP_BOT_NOTIFY_SECRET'),

    'bot_notify_timeout' => (int) env('WHATSAPP_BOT_NOTIFY_TIMEOUT', 8),

];
