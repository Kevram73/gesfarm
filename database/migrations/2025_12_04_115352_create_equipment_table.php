<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['TRACTOR', 'HARVESTER', 'IRRIGATION', 'PLANTING', 'SPRAYING', 'HANDLING', 'STORAGE', 'OTHER']);
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->enum('status', ['ACTIVE', 'MAINTENANCE', 'BROKEN', 'SOLD', 'RETIRED'])->default('ACTIVE');
            $table->date('maintenance_date')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('min_stock', 10, 2)->nullable();
            $table->decimal('max_stock', 10, 2)->nullable();
            $table->string('unit')->nullable();
            $table->boolean('is_low_stock')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
