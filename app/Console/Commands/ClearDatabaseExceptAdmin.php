<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class ClearDatabaseExceptAdmin extends Command
{
    protected $signature = 'db:clear-except-admin
                            {--force : Skip confirmation prompt}';

    protected $description = 'Delete all application data except super_admin user(s)';

    /**
     * @var list<string>
     */
    private array $tablesToTruncate = [
        'order_items',
        'order_portal_passwords',
        'customer_requests',
        'tips',
        'payments',
        'feedback',
        'waiter_salary_payments',
        'waiter_restaurant_assignments',
        'orders',
        'admin_activity_logs',
        'admin_sent_notifications',
        'notifications',
        'activities',
        'withdrawals',
        'menu_items',
        'categories',
        'tables',
        'bots',
        'restaurants',
        'settings',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens',
        'sessions',
    ];

    public function handle(): int
    {
        $adminIds = User::role('super_admin')->pluck('id');

        if ($adminIds->isEmpty()) {
            $this->error('No super_admin user found. Create one first, then run again.');

            return self::FAILURE;
        }

        $this->warn('This will permanently delete all data except super_admin user(s).');
        $this->info('Keeping '.$adminIds->count().' super_admin account(s):');

        User::query()
            ->whereIn('id', $adminIds)
            ->get(['id', 'name', 'email'])
            ->each(fn (User $user) => $this->line("  • {$user->email} ({$user->name})"));

        if (! $this->option('force') && ! $this->confirm('Continue?', false)) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->tablesToTruncate as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                    $this->line("  Cleared: {$table}");
                }
            }

            $userModel = User::class;

            DB::table('model_has_roles')
                ->where('model_type', $userModel)
                ->whereNotIn('model_id', $adminIds)
                ->delete();

            DB::table('model_has_permissions')
                ->where('model_type', $userModel)
                ->whereNotIn('model_id', $adminIds)
                ->delete();

            DB::table('personal_access_tokens')
                ->where('tokenable_type', $userModel)
                ->whereNotIn('tokenable_id', $adminIds)
                ->delete();

            $deletedUsers = User::query()->whereNotIn('id', $adminIds)->delete();

            User::query()
                ->whereIn('id', $adminIds)
                ->update([
                    'restaurant_id' => null,
                    'waiter_code' => null,
                    'employment_type' => null,
                    'linked_until' => null,
                    'global_waiter_number' => null,
                    'phone' => null,
                    'location' => null,
                    'profile_photo_path' => null,
                    'is_online' => false,
                    'last_online_at' => null,
                ]);
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->newLine();
        $this->info("Done. Removed {$deletedUsers} non-admin user(s). Super admin account(s) kept.");

        return self::SUCCESS;
    }
}
