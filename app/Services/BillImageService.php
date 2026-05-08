<?php

namespace App\Services;

use App\Models\Order;

class BillImageService
{
    public function renderPng(Order $order): string
    {
        $width = 900;
        $height = 1200;

        $image = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($image, 255, 255, 255);
        $dark = imagecolorallocate($image, 17, 24, 39);
        $muted = imagecolorallocate($image, 107, 114, 128);
        $accent = imagecolorallocate($image, 79, 70, 229);
        $line = imagecolorallocate($image, 229, 231, 235);

        imagefilledrectangle($image, 0, 0, $width, $height, $white);
        imagefilledrectangle($image, 0, 0, $width, 130, $accent);

        imagestring($image, 5, 40, 30, 'TIPTAP BILL', $white);
        imagestring($image, 4, 40, 70, $this->sanitize($order->restaurant?->name ?? 'Restaurant'), $white);
        imagestring($image, 3, 40, 95, 'Order #'.$order->id.' | Table '.$this->sanitize((string) ($order->table_number ?? '-')), $white);

        $y = 170;
        imagestring($image, 4, 40, $y, 'Customer: '.$this->sanitize($order->customer_name ?: 'Guest'), $dark);
        $y += 30;
        imagestring($image, 3, 40, $y, 'Phone: '.$this->sanitize($order->customer_phone ?: '-'), $muted);
        $y += 35;

        imageline($image, 40, $y, 860, $y, $line);
        $y += 20;

        imagestring($image, 3, 40, $y, 'ITEM', $muted);
        imagestring($image, 3, 500, $y, 'QTY', $muted);
        imagestring($image, 3, 620, $y, 'PRICE', $muted);
        imagestring($image, 3, 760, $y, 'TOTAL', $muted);
        $y += 18;
        imageline($image, 40, $y, 860, $y, $line);
        $y += 15;

        foreach ($order->items as $item) {
            $name = $this->sanitize((string) ($item->name ?: 'Item'));
            $name = mb_strimwidth($name, 0, 48, '...');

            imagestring($image, 4, 40, $y, $name, $dark);
            imagestring($image, 4, 500, $y, (string) $item->quantity, $dark);
            imagestring($image, 4, 620, $y, $this->money((float) $item->price), $dark);
            imagestring($image, 4, 760, $y, $this->money((float) $item->total), $dark);
            $y += 30;
        }

        $y += 10;
        imageline($image, 40, $y, 860, $y, $line);
        $y += 24;

        imagestring($image, 5, 560, $y, 'TOTAL:', $dark);
        imagestring($image, 5, 690, $y, 'TZS '.$this->money((float) $order->total_amount), $accent);
        $y += 40;

        imagestring($image, 3, 40, $y, 'Status: '.strtoupper((string) $order->status), $muted);
        $y += 25;
        imagestring($image, 2, 40, $y, 'Generated: '.now()->format('Y-m-d H:i:s'), $muted);

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function sanitize(string $value): string
    {
        return preg_replace('/[^\x20-\x7E]/', '', $value) ?: '-';
    }

    private function money(float $amount): string
    {
        return number_format($amount, 0);
    }
}
