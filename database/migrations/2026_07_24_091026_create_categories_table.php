<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable(); // path di disk 'public', mis. categories/xxx.jpg
            $table->string('icon')->default('utensils'); // utensils | drink | cake | dumpling
            $table->string('color')->default('orange'); // orange | teal
            // Placeholder sampai modul Menu/Items dibuat. Nanti bisa diganti
            // jadi accessor withCount('menuItems') begitu relasinya ada.
            $table->unsignedInteger('items_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};