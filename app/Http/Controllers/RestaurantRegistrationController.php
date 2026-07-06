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
    private const SESSION_KEY = 'restaurant_registration.credentials';

    public function create(): View
    {
        return view('auth.register-restaurant');
    }

    public function storeCredentials(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'manager_email' => 'required|email|max:255|unique:users,email',
            'manager_password' => 'required|confirmed|min:8',
        ]);

        $request->session()->put(self::SESSION_KEY, [
            'manager_email' => $validated['manager_email'],
            'manager_password' => $validated['manager_password'],
        ]);

        return redirect()->route('restaurant.register.details');
    }

    public function createDetails(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has(self::SESSION_KEY)) {
            return redirect()->route('restaurant.register');
        }

        return view('auth.register-restaurant-details', [
            'managerEmail' => $request->session()->get(self::SESSION_KEY.'.manager_email'),
        ]);
    }

    public function storeDetails(Request $request): RedirectResponse
    {
        $credentials = $request->session()->get(self::SESSION_KEY);

        if (! is_array($credentials) || empty($credentials['manager_email']) || empty($credentials['manager_password'])) {
            return redirect()->route('restaurant.register');
        }

        $validated = $request->validate([
            'manager_name' => 'required|string|max:255',
            'restaurant_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if (User::where('email', $credentials['manager_email'])->exists()) {
            $request->session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('restaurant.register')
                ->withErrors(['manager_email' => 'This email is already registered. Please sign in instead.'])
                ->withInput(['manager_email' => $credentials['manager_email']]);
        }

        if (! Role::where('name', 'manager')->where('guard_name', 'web')->exists()) {
            $this->seedRolesIfMissing();
        }

        if (! Role::where('name', 'manager')->where('guard_name', 'web')->exists()) {
            return back()
                ->withInput()
                ->withErrors(['restaurant_name' => 'System roles are not configured. Please run: php artisan db:seed --class=RolesAndPermissionsSeeder']);
        }

        try {
            $manager = DB::transaction(function () use ($validated, $credentials) {
                $restaurant = Restaurant::create([
                    'name' => $validated['restaurant_name'],
                    'location' => $validated['location'],
                    'phone' => $validated['phone'],
                    'is_active' => false,
                    'approval_status' => Restaurant::STATUS_PENDING,
                ]);

                $manager = User::create([
                    'name' => $validated['manager_name'],
                    'email' => $credentials['manager_email'],
                    'auth_provider' => 'email',
                    'password' => Hash::make($credentials['manager_password']),
                    'restaurant_id' => $restaurant->id,
                    'phone' => $validated['phone'],
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

        $request->session()->forget(self::SESSION_KEY);

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
