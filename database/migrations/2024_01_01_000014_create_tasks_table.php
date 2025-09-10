<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type'); // 'agricultural', 'livestock', 'maintenance', 'administrative'
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->date('due_date');
            $table->date('completed_date')->nullable();
            $table->foreignId('assigned_to')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
