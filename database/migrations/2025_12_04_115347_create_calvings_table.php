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
        Schema::create('calvings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('mother_id')->constrained('livestock')->cascadeOnDelete();
            $table->foreignId('breeding_id')->nullable()->unique()->constrained('breedings')->nullOnDelete();
            $table->foreignId('created_by_id')->constrained('users');
            $table->date('date');
            $table->string('type');
            $table->integer('offspring_count')->default(1);
            $table->text('complications')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calvings');
    }
};
