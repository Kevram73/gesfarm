<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'income', 'expense', 'transfer'
            $table->string('category'); // 'feed', 'medicine', 'equipment', 'sales', etc.
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('XOF');
            $table->date('transaction_date');
            $table->string('payment_method')->nullable(); // 'cash', 'bank_transfer', 'check'
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // URLs des documents
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_entity_id')->nullable(); // ID de l'entité liée (flock, cattle, etc.)
            $table->string('related_entity_type')->nullable(); // Type d'entité liée
            $table->timestamps();
            
            $table->index(['type', 'category']);
            $table->index('transaction_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
