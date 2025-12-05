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
        Schema::create('livestock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('livestock')->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['CATTLE', 'SHEEP', 'GOAT', 'PIG', 'CHICKEN', 'DUCK', 'TURKEY', 'RABBIT', 'FISH', 'OTHER']);
            $table->string('breed')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('gender')->nullable();
            $table->enum('status', ['ACTIVE', 'SOLD', 'SLAUGHTERED', 'DEAD', 'TRANSFERRED'])->default('ACTIVE');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('min_stock', 10, 2)->nullable();
            $table->decimal('max_stock', 10, 2)->nullable();
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
        Schema::dropIfExists('livestock');
    }
};
