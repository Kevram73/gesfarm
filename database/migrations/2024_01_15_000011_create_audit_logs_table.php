<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event'); // 'created', 'updated', 'deleted', 'login', 'logout', etc.
            $table->string('model_type'); // Type de modèle affecté
            $table->unsignedBigInteger('model_id')->nullable(); // ID du modèle affecté
            $table->json('old_values')->nullable(); // Valeurs avant modification
            $table->json('new_values')->nullable(); // Valeurs après modification
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('created_at');
            
            $table->index(['event', 'model_type']);
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
