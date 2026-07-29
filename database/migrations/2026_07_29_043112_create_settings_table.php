<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // --- Restaurant Info ---
            $table->string('restaurant_name')->default('My Restaurant');
            $table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable(); // path di disk 'public'
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            // --- Business Hours (per hari: open, close, closed) ---
            $table->json('business_hours')->nullable();

            // --- Tax & Pricing ---
            $table->decimal('tax_rate', 5, 2)->default(8.50); // dalam persen
            $table->string('currency', 3)->default('USD');

            // --- Printer Settings ---
            $table->string('receipt_printer')->nullable();
            $table->string('kitchen_printer')->nullable();
            $table->boolean('auto_print_kitchen')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};