<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_prepared')->default(false)->after('note');
            $table->boolean('is_allergy')->default(false)->after('note'); // tampilkan 'note' dengan ikon peringatan
            $table->string('side')->nullable()->after('is_allergy'); // mis. "Side: Asparagus"
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['is_prepared', 'is_allergy', 'side']);
        });
    }
};