<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incubation_records', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->string('egg_type'); // 'chicken', 'duck', 'turkey'
            $table->string('breed');
            $table->integer('egg_count');
            $table->date('start_date');
            $table->integer('incubation_days');
            $table->decimal('temperature', 4, 1); // in Celsius
            $table->decimal('humidity_percentage', 4, 1);
            $table->string('egg_size'); // small, medium, large
            $table->integer('hatched_count')->default(0);
            $table->integer('unhatched_count')->default(0);
            $table->decimal('hatch_rate', 5, 2)->default(0); // percentage
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incubation_records');
    }
};
