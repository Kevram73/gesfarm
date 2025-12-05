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
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('male_id')->constrained('livestock')->cascadeOnDelete();
            $table->foreignId('female_id')->constrained('livestock')->cascadeOnDelete();
            $table->foreignId('created_by_id')->constrained('users');
            $table->date('date');
            $table->string('type')->default('NATURAL');
            $table->boolean('success')->nullable();
            $table->date('expected_calving_date')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breedings');
    }
};
