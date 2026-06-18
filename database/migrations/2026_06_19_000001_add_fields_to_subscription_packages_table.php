<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (! Schema::hasColumn('subscription_packages', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('subscription_packages', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
            if (! Schema::hasColumn('subscription_packages', 'tagline')) {
                $table->string('tagline')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('subscription_packages', 'description')) {
                $table->text('description')->nullable()->after('tagline');
            }
            if (! Schema::hasColumn('subscription_packages', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('description');
            }
            if (! Schema::hasColumn('subscription_packages', 'currency')) {
                $table->string('currency', 8)->default('TZS')->after('price');
            }
            if (! Schema::hasColumn('subscription_packages', 'billing_period')) {
                $table->string('billing_period', 20)->default('monthly')->after('currency');
            }
            if (! Schema::hasColumn('subscription_packages', 'trial_days')) {
                $table->unsignedInteger('trial_days')->default(0)->after('billing_period');
            }
            if (! Schema::hasColumn('subscription_packages', 'table_limit')) {
                $table->unsignedInteger('table_limit')->nullable()->after('trial_days');
            }
            if (! Schema::hasColumn('subscription_packages', 'features')) {
                $table->json('features')->nullable()->after('table_limit');
            }
            if (! Schema::hasColumn('subscription_packages', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('features');
            }
            if (! Schema::hasColumn('subscription_packages', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
            if (! Schema::hasColumn('subscription_packages', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'slug', 'tagline', 'description', 'price', 'currency',
                'billing_period', 'trial_days', 'table_limit', 'features',
                'is_featured', 'is_active', 'sort_order',
            ]);
        });
    }
};
