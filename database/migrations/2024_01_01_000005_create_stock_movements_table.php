<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items');
            $table->string('type'); // 'in', 'out', 'adjustment'
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->date('movement_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
