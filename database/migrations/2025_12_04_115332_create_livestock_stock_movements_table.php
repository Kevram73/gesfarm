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
        Schema::create('livestock_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained('livestock')->cascadeOnDelete();
            $table->foreignId('created_by_id')->constrained('users');
            $table->enum('type', ['PURCHASE', 'SALE', 'BIRTH', 'DEATH', 'SLAUGHTER', 'TRANSFER', 'ADJUSTMENT']);
            $table->decimal('quantity', 10, 2);
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_stock_movements');
    }
};
