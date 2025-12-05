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
        Schema::create('egg_incubations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('livestock_id')->nullable()->constrained('livestock')->nullOnDelete();
            $table->foreignId('created_by_id')->constrained('users');
            $table->date('start_date');
            $table->string('poultry_type');
            $table->string('breed')->nullable();
            $table->integer('egg_count');
            $table->string('egg_size')->nullable();
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->integer('incubation_days');
            $table->date('expected_hatch_date')->nullable();
            $table->date('actual_hatch_date')->nullable();
            $table->integer('hatched_count')->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('IN_PROGRESS');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_incubations');
    }
};
