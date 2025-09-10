<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique();
            $table->foreignId('category_id')->constrained('stock_categories');
            $table->string('unit'); // kg, liter, piece, etc.
            $table->decimal('current_quantity', 10, 2)->default(0);
            $table->decimal('minimum_quantity', 10, 2)->default(0);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('supplier')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
