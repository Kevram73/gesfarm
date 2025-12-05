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
        Schema::create('chicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('livestock_id')->constrained('livestock')->cascadeOnDelete();
            $table->foreignId('egg_incubation_id')->nullable()->constrained('egg_incubations')->nullOnDelete();
            $table->foreignId('created_by_id')->constrained('users');
            $table->string('name')->nullable();
            $table->date('hatch_date');
            $table->decimal('initial_weight', 8, 2)->nullable();
            $table->decimal('current_weight', 8, 2)->nullable();
            $table->integer('age')->nullable();
            $table->string('status')->default('ACTIVE');
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
        Schema::dropIfExists('chicks');
    }
};
