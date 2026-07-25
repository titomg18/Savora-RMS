<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Dipakai buat filter tab di halaman Kitchen (All / Grill / Prep / dst).
            $table->enum('station', ['grill', 'prep', 'other'])->default('other')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('station');
        });
    }
};