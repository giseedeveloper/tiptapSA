<?php

namespace App\Observers;

use App\Jobs\SendBillImageToCustomer;
use App\Models\Order;
use Throwable;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status') || $order->status !== 'served') {
            return;
        }

        if (filled($order->whatsapp_jid)) {
            return;
        }

        $jid = Order::normalizeWhatsAppJid(null, $order->customer_phone);
        if ($jid === null || $jid === '') {
            return;
        }

        try {
            SendBillImageToCustomer::dispatchSync($order->id);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
