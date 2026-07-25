<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Status di layar dapur (KDS), terpisah dari 'status' (held/submitted/completed/cancelled)
            // yang dipakai di halaman Orders/kasir.
            $table->enum('kitchen_status', ['waiting', 'cooking', 'ready', 'served'])
                ->default('waiting')
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('kitchen_status');
        });
    }
};