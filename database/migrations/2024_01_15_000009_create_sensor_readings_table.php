<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 10, 4);
            $table->string('unit'); // '°C', '%', 'pH', 'mg/L', etc.
            $table->timestamp('reading_time');
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->enum('status', ['normal', 'warning', 'critical'])->default('normal');
            $table->timestamps();
            
            $table->index(['sensor_id', 'reading_time']);
            $table->index(['status', 'reading_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_readings');
    }
};
