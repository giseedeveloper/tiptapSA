<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('whatsapp_jid', 191)->nullable()->after('customer_name');
            $table->timestamp('bill_image_pushed_at')->nullable()->after('whatsapp_jid');

            $table->index('whatsapp_jid');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex(['whatsapp_jid']);
            $table->dropColumn(['whatsapp_jid', 'bill_image_pushed_at']);
        });
    }
};
