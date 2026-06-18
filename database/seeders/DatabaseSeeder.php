<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SubscriptionPackageSeeder::class);

        // Create Super Admin
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'Tadmin@tiptap.so.za',
            'password' => 'Tiptapsoza@2026',
        ]);
        $admin->assignRole('super_admin');
    }
}
