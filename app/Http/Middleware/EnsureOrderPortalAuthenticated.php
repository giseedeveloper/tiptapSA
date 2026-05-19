<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrderPortalAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $restaurantId = session('order_portal_restaurant_id');
        $userId = session('order_portal_user_id');

        if (! $restaurantId || ! $userId) {
            $bearer = $request->bearerToken();
            if ($bearer) {
                $payload = Cache::get('order_portal_token:'.$bearer);
                if (is_array($payload) && isset($payload['restaurant_id'], $payload['user_id'])) {
                    session([
                        'order_portal_restaurant_id' => $payload['restaurant_id'],
                        'order_portal_user_id' => $payload['user_id'],
                    ]);

                    return $next($request);
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please sign in to TIPTAP ORDER first.',
                    'error' => 'unauthenticated',
                ], 401);
            }

            return redirect()->route('order-portal.login')
                ->with('error', 'Please sign in to TIPTAP ORDER first.');
        }

        return $next($request);
    }
}
