<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poultry_flocks', function (Blueprint $table) {
            $table->id();
            $table->string('flock_number')->unique();
            $table->string('type'); // 'layer', 'broiler', 'duck', 'turkey'
            $table->string('breed');
            $table->integer('initial_quantity');
            $table->integer('current_quantity');
            $table->date('arrival_date');
            $table->integer('age_days');
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->string('status')->default('active'); // active, sold, deceased
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_flocks');
    }
};
