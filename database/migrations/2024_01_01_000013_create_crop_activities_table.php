<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crop_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained('crops');
            $table->string('activity_type'); // 'planting', 'fertilizing', 'irrigation', 'pest_control', 'harvesting'
            $table->date('activity_date');
            $table->text('description');
            $table->json('materials_used')->nullable(); // stock items used
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_activities');
    }
};
