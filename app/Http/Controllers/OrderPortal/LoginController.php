<?php

namespace App\Http\Controllers\OrderPortal;

use App\Http\Controllers\Controller;
use App\Models\OrderPortalPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('order-portal.login');
    }

    /**
     * Login with password only. Password is unique per waiter/restaurant;
     * system identifies which restaurant (and waiter) from the password.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'password' => 'required|string|max:50',
        ]);

        $plain = $request->password;

        $credential = OrderPortalPassword::query()
            ->whereNull('revoked_at')
            ->with(['user', 'restaurant'])
            ->get()
            ->first(fn ($c) => $c->checkPassword($plain));

        if (! $credential) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password or it has expired. Ask your manager for a new one.',
                ], 422);
            }

            return back()->with('error', 'Incorrect password or it has expired. Ask your manager for a new one.');
        }

        $user = $credential->user;
        if (! $user->hasRole('waiter') || $user->restaurant_id != $credential->restaurant_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to the Order Portal. Contact your manager.',
                ], 403);
            }

            return back()->with('error', 'You do not have access to the Order Portal. Contact your manager.');
        }

        session([
            'order_portal_restaurant_id' => $credential->restaurant_id,
            'order_portal_user_id' => $user->id,
        ]);

        if ($request->expectsJson()) {
            $token = Str::random(64);
            Cache::put('order_portal_token:'.$token, [
                'restaurant_id' => $credential->restaurant_id,
                'user_id' => $user->id,
            ], now()->addDays(30));

            return response()->json([
                'success' => true,
                'message' => 'Signed in successfully.',
                'data' => [
                    'token' => $token,
                    'restaurant_id' => $credential->restaurant_id,
                    'restaurant_name' => $credential->restaurant?->name,
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? null,
                ],
            ]);
        }

        return redirect()->route('order-portal.orders')->with('success', 'Signed in successfully.');
    }

    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        $bearer = $request->bearerToken();
        if ($bearer) {
            Cache::forget('order_portal_token:'.$bearer);
        }
        session()->forget(['order_portal_restaurant_id', 'order_portal_user_id']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Umetoka.',
            ]);
        }

        return redirect()->route('order-portal.login')->with('success', 'Umetoka.');
    }
}
