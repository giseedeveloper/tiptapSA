<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = $this->buildStats();

        $recent_restaurants = Restaurant::query()->latest()->take(5)->get();
        $recent_activities = Activity::query()->with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_restaurants', 'recent_activities'));
    }

    public function getStats(): JsonResponse
    {
        return response()->json($this->buildStats());
    }

    /**
     * @return array<string, int|float>
     */
    private function buildStats(): array
    {
        return [
            'total_restaurants' => Restaurant::query()->count(),
            'total_waiters' => User::role('waiter')->count(),
            'active_orders' => Order::query()
                ->whereIn('status', ['pending', 'preparing', 'ready'])
                ->count(),
            'total_revenue' => (float) Payment::query()
                ->whereIn('status', ['paid', 'completed'])
                ->sum('amount'),
            'pending_withdrawals' => Withdrawal::query()
                ->where('status', 'pending')
                ->count(),
        ];
    }
}
