<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\BillImageService;
use Illuminate\Http\Request;

class BillImageController extends Controller
{
    public function __invoke(Request $request, int $orderId, BillImageService $billImageService)
    {
        $order = Order::withoutGlobalScopes()
            ->with(['restaurant', 'items'])
            ->findOrFail($orderId);

        if (! hash_equals($order->billImageSignature(), (string) $request->query('signature'))) {
            abort(403);
        }

        return response($billImageService->renderPng($order), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
