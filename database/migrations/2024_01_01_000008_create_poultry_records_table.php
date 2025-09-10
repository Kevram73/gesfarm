<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poultry_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flock_id')->constrained('poultry_flocks');
            $table->date('record_date');
            $table->integer('eggs_collected')->default(0);
            $table->decimal('feed_consumed', 8, 2)->default(0); // in kg
            $table->integer('mortality_count')->default(0);
            $table->decimal('average_weight', 6, 2)->nullable(); // in kg
            $table->text('health_notes')->nullable();
            $table->text('observations')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_records');
    }
};
