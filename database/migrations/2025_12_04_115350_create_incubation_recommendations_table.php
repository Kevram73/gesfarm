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
        Schema::create('incubation_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('poultry_type')->unique();
            $table->string('breed')->nullable();
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->integer('incubation_days');
            $table->string('egg_size')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incubation_recommendations');
    }
};
