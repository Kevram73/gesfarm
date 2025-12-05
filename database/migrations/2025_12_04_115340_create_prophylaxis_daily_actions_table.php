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
        Schema::create('prophylaxis_daily_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prophylaxis_id')->constrained('prophylaxis')->cascadeOnDelete();
            $table->foreignId('completed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('day');
            $table->date('date');
            $table->string('action');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prophylaxis_daily_actions');
    }
};
