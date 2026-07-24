<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number'); // ditampilkan sebagai 01, 02, dst
            $table->unsignedInteger('seats')->default(4);
            $table->enum('area', ['main', 'patio', 'bar'])->default('main');
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->string('label')->nullable();    // mis. "1h 15m" (durasi) atau "19:30" (jam reservasi)
            $table->string('subtitle')->nullable();  // mis. "Order #4092" atau "Smith Party"
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['area', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dining_tables');
    }
};