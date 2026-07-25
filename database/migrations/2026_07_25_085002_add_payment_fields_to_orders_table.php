<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid')->after('kitchen_status');
            $table->enum('payment_method', ['cash', 'card', 'qris', 'ewallet'])->nullable()->after('payment_status');
            $table->decimal('discount', 10, 2)->default(0)->after('total');
            $table->string('promo_code')->nullable()->after('discount');
            $table->timestamp('paid_at')->nullable()->after('promo_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'discount', 'promo_code', 'paid_at']);
        });
    }
};