<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // 'agricultural_inputs', 'animal_feed', 'equipment', 'veterinary_products'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_categories');
    }
};
