<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(Request $request): View
    {
        $query = Restaurant::query()
            ->withCount(['users' => function ($q) {
                $q->role('manager');
            }])
            ->withCount(['users as waiters_count' => function ($q) {
                $q->role('waiter');
            }])
            ->latest();

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($qry) use ($search) {
                $qry->where('name', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            }
            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $restaurants = $query->paginate(10)->withQueryString();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function show(string $id): View
    {
        $restaurant = Restaurant::query()
            ->with(['users' => function ($query) {
                $query->role(['manager', 'waiter']);
            }])
            ->findOrFail($id);

        $managers = $restaurant->users->filter(fn (User $user) => $user->hasRole('manager'));
        $waiters = $restaurant->users->filter(fn (User $user) => $user->hasRole('waiter'));

        $overview = $this->buildOverviewStats($restaurant);

        return view('admin.restaurants.show', compact('restaurant', 'managers', 'waiters', 'overview'));
    }

    public function edit(string $id): View
    {
        $restaurant = Restaurant::query()->findOrFail($id);

        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $restaurant = Restaurant::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'selcom_vendor_id' => 'nullable|string|max:255',
            'selcom_api_key' => 'nullable|string|max:255',
            'selcom_api_secret' => 'nullable|string|max:255',
            'selcom_is_live' => 'nullable|boolean',
        ]);

        $validated['selcom_is_live'] = $request->has('selcom_is_live');

        $restaurant->update($validated);

        return redirect()
            ->route('admin.restaurants.show', $restaurant)
            ->with('success', 'Restaurant updated successfully.');
    }

    public function toggleStatus(string $id): RedirectResponse
    {
        $restaurant = Restaurant::query()->findOrFail($id);
        $oldActive = $restaurant->is_active;
        $restaurant->is_active = ! $restaurant->is_active;
        $restaurant->save();

        AdminActivityLog::log(
            'restaurant.toggle_status',
            'restaurant',
            (int) $restaurant->id,
            ['is_active' => $oldActive, 'name' => $restaurant->name],
            ['is_active' => $restaurant->is_active, 'name' => $restaurant->name],
            null
        );

        $status = $restaurant->is_active ? 'activated' : 'blocked';

        return back()->with('success', "Restaurant has been {$status}.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $restaurant = Restaurant::query()->findOrFail($id);
        $name = $restaurant->name;
        $restaurant->delete();

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', "Restaurant \"{$name}\" deleted successfully.");
    }

    /**
     * @return array{total_earnings: float, total_orders: int, avg_rating: float}
     */
    private function buildOverviewStats(Restaurant $restaurant): array
    {
        $totalEarnings = (float) Payment::query()
            ->where(function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id)
                    ->orWhereHas('order', fn ($orderQuery) => $orderQuery
                        ->withoutGlobalScopes()
                        ->where('restaurant_id', $restaurant->id));
            })
            ->whereIn('status', ['paid', 'completed'])
            ->sum('amount');

        $totalOrders = Order::query()
            ->withoutGlobalScopes()
            ->where('restaurant_id', $restaurant->id)
            ->count();

        $avgRating = Feedback::query()
            ->withoutGlobalScopes()
            ->where('restaurant_id', $restaurant->id)
            ->avg('rating');

        return [
            'total_earnings' => $totalEarnings,
            'total_orders' => $totalOrders,
            'avg_rating' => round((float) ($avgRating ?? 0), 1),
        ];
    }
}
