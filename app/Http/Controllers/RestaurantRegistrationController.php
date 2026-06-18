<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class RestaurantRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register-restaurant');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|unique:users,email',
            'manager_password' => 'required|confirmed|min:8',
        ]);

        if (! Role::where('name', 'manager')->where('guard_name', 'web')->exists()) {
            $this->seedRolesIfMissing();
        }

        if (! Role::where('name', 'manager')->where('guard_name', 'web')->exists()) {
            return back()
                ->withInput()
                ->withErrors(['restaurant_name' => 'System roles are not configured. Please run: php artisan db:seed --class=RolesAndPermissionsSeeder']);
        }

        try {
            $manager = DB::transaction(function () use ($validated) {
                $restaurant = Restaurant::create([
                    'name' => $validated['restaurant_name'],
                    'location' => $validated['location'],
                    'phone' => $validated['phone'],
                    'is_active' => false,
                    'approval_status' => Restaurant::STATUS_PENDING,
                ]);

                $manager = User::create([
                    'name' => $validated['manager_name'],
                    'email' => $validated['manager_email'],
                    'password' => Hash::make($validated['manager_password']),
                    'restaurant_id' => $restaurant->id,
                ]);

                $manager->assignRole('manager');

                return $manager;
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'restaurant_name' => 'Registration failed. Please try again or contact support if the problem continues.',
                ]);
        }

        Auth::login($manager);

        return redirect()
            ->route('manager.onboarding.waiting')
            ->with('status', 'Registration received! Your restaurant is awaiting approval.');
    }

    private function seedRolesIfMissing(): void
    {
        (new RolesAndPermissionsSeeder)->run();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
