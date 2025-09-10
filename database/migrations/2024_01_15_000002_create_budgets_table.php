<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // 'feed', 'medicine', 'equipment', 'maintenance', etc.
            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('XOF');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};
