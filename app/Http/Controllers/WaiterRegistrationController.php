<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaiterRegistrationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WaiterRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register-waiter');
    }

    public function store(WaiterRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => trim($validated['first_name'].' '.$validated['last_name']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'location' => $validated['location'] ?? null,
            'restaurant_id' => null,
            'waiter_code' => null,
            'global_waiter_number' => User::generateGlobalWaiterNumber(),
        ]);

        $user->assignRole('waiter');

        Auth::login($user);

        return redirect()->route('waiter.dashboard')
            ->with('success', 'Your account has been created. Your unique number: '.$user->global_waiter_number.'. Ask a restaurant manager to link you.');
    }
}
