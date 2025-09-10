<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'temperature', 'humidity', 'water_quality', 'air_quality', etc.
            $table->string('model')->nullable();
            $table->string('serial_number')->unique();
            $table->string('location')->nullable();
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->json('configuration')->nullable(); // Configuration du capteur
            $table->enum('status', ['active', 'inactive', 'maintenance', 'error'])->default('active');
            $table->timestamp('last_reading_at')->nullable();
            $table->json('last_reading')->nullable(); // Dernière lecture
            $table->decimal('battery_level', 5, 2)->nullable(); // Niveau de batterie
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['type', 'status']);
            $table->index('last_reading_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensors');
    }
};
