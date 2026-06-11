<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\User;
use App\Services\ManagerDashboardAnalytics;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $restaurantId = Auth::user()->restaurant_id;
        $today = Carbon::today();

        $stats = $this->buildStats($restaurantId, $today);

        $pendingOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $preparingOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'preparing')
            ->latest()
            ->get();

        $servedOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'served')
            ->latest()
            ->get();

        $paidOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(10)
            ->get();

        $recentFeedback = Feedback::query()->forService()->with(['order', 'waiter'])
            ->where('restaurant_id', $restaurantId)
            ->latest()
            ->take(5)
            ->get();

        $analytics = app(ManagerDashboardAnalytics::class)->forRestaurant($restaurantId);

        return view('manager.dashboard', [
            'totalOrdersToday' => $stats['total_orders_today'],
            'revenueToday' => $stats['revenue_today'],
            'avgRating' => $stats['avg_rating'],
            'waitersOnline' => $stats['waiters_online'],
            'pendingOrders' => $pendingOrders,
            'preparingOrders' => $preparingOrders,
            'servedOrders' => $servedOrders,
            'paidOrders' => $paidOrders,
            'recentFeedback' => $recentFeedback,
            'analytics' => $analytics,
        ]);
    }

    public function getStats(): JsonResponse
    {
        $restaurantId = Auth::user()->restaurant_id;
        $stats = $this->buildStats($restaurantId, Carbon::today());

        return response()->json([
            'total_orders_today' => $stats['total_orders_today'],
            'revenue_today' => $stats['revenue_today'],
            'avg_rating' => number_format($stats['avg_rating'], 1),
            'waiters_online' => $stats['waiters_online'],
        ]);
    }

    public function getAnalytics(): JsonResponse
    {
        $restaurantId = Auth::user()->restaurant_id;
        $analytics = app(ManagerDashboardAnalytics::class)->forRestaurant($restaurantId);

        return response()->json([
            'weekly_trend' => $analytics['weekly_trend'],
            'hourly_activity' => $analytics['hourly_activity'],
            'week_comparison' => $analytics['week_comparison'],
            'insights' => $analytics['insights'],
        ]);
    }

    /**
     * @return array{total_orders_today: int, revenue_today: float, avg_rating: float, waiters_online: int}
     */
    private function buildStats(int $restaurantId, Carbon $today): array
    {
        $revenueToday = app(ManagerDashboardAnalytics::class)
            ->revenueForPaidOrdersOnDate($restaurantId, $today);

        return [
            'total_orders_today' => Order::query()
                ->where('restaurant_id', $restaurantId)
                ->whereDate('created_at', $today)
                ->count(),
            'revenue_today' => $revenueToday,
            'avg_rating' => (float) (Feedback::query()
                ->forService()
                ->where('restaurant_id', $restaurantId)
                ->avg('rating') ?? 0),
            'waiters_online' => User::role('waiter')
                ->where('restaurant_id', $restaurantId)
                ->where('is_online', true)
                ->count(),
        ];
    }
}
