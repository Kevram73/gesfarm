<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cattle_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cattle_id')->constrained('cattle');
            $table->date('record_date');
            $table->decimal('milk_production', 8, 2)->default(0); // in liters
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('health_status')->default('healthy'); // healthy, sick, treated
            $table->text('health_notes')->nullable();
            $table->text('feeding_notes')->nullable();
            $table->text('observations')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cattle_records');
    }
};
