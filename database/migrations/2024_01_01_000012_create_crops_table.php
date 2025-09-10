<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('variety');
            $table->foreignId('zone_id')->constrained('zones');
            $table->date('planting_date');
            $table->date('expected_harvest_date')->nullable();
            $table->date('actual_harvest_date')->nullable();
            $table->decimal('planted_area', 10, 2); // in square meters
            $table->decimal('expected_yield', 10, 2)->nullable(); // in kg
            $table->decimal('actual_yield', 10, 2)->nullable(); // in kg
            $table->string('status')->default('planted'); // planted, growing, harvested, failed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
