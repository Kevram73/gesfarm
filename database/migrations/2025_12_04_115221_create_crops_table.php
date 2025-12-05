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
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['VEGETABLE', 'FRUIT', 'GRAIN', 'LEGUME', 'HERB', 'SPICE', 'OTHER']);
            $table->string('variety')->nullable();
            $table->enum('category', ['ORGANIC', 'CONVENTIONAL', 'HYDROPONIC', 'GREENHOUSE'])->default('CONVENTIONAL');
            $table->text('description')->nullable();
            $table->string('planting_season')->nullable();
            $table->string('harvest_season')->nullable();
            $table->integer('growth_period')->nullable()->comment('en jours');
            $table->string('water_needs')->nullable();
            $table->string('soil_requirements')->nullable();
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->string('unit')->nullable();
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
        Schema::dropIfExists('crops');
    }
};
