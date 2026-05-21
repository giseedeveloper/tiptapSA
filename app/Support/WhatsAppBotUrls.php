<?php

namespace App\Support;

class WhatsAppBotUrls
{
    /**
     * Base URL of the Node bot (no trailing path), derived from WHATSAPP_BOT_NOTIFY_URL.
     *
     * Example: https://wa-notify.example.com/notify → https://wa-notify.example.com
     */
    public static function botBaseUrl(): ?string
    {
        $notify = rtrim((string) config('whatsapp.bot_notify_url', ''), '/');

        if ($notify === '') {
            return null;
        }

        $base = preg_replace('#/notify$#', '', $notify);

        return $base !== '' ? $base : null;
    }

    /**
     * Laravel → Node forward target when Meta posts to /api/whatsapp/webhook.
     */
    public static function inboundForwardUrl(): ?string
    {
        $base = self::botBaseUrl();

        return $base !== null ? $base.'/inbound' : null;
    }

    /**
     * Suggested Meta callback when Laravel verifies and forwards (forwarder mode).
     */
    public static function laravelWebhookUrl(): string
    {
        return rtrim((string) config('app.url'), '/').'/api/whatsapp/webhook';
    }

    /**
     * Suggested Meta callback when Meta talks to the bot host directly.
     */
    public static function botWebhookUrl(): ?string
    {
        $base = self::botBaseUrl();

        return $base !== null ? $base.'/webhook' : null;
    }
}
