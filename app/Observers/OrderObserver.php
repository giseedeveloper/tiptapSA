<?php

namespace App\Observers;

use App\Jobs\SendBillImageToCustomer;
use App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        if ($order->shouldPushBillImage()) {
            SendBillImageToCustomer::dispatch($order->id);
        }
    }

    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if ($order->shouldPushBillImage()) {
            SendBillImageToCustomer::dispatch($order->id);
        }
    }
}
