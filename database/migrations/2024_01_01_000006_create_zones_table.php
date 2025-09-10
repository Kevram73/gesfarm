<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // 'cultivation', 'pasture', 'enclosure', 'building', 'water_point'
            $table->json('coordinates'); // GeoJSON format
            $table->decimal('area', 10, 2)->nullable(); // in square meters
            $table->string('status')->default('active'); // active, inactive, maintenance
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
