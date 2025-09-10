<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->onDelete('cascade');
            $table->string('product_type'); // 'eggs', 'chicken', 'milk', 'crops', etc.
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // 'pieces', 'kg', 'liters', 'dozens', etc.
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 15, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['sales_order_id', 'product_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
};
