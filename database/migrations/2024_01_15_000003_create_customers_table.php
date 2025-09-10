<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Burkina Faso');
            $table->string('customer_type')->default('individual'); // 'individual', 'business'
            $table->string('tax_number')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['status', 'customer_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
