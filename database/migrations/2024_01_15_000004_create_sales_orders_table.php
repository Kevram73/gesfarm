<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->string('currency', 3)->default('XOF');
            $table->string('payment_status')->default('pending'); // 'pending', 'partial', 'paid', 'overdue'
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->text('delivery_address')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['status', 'order_date']);
            $table->index('payment_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_orders');
    }
};
