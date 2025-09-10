<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'stock_alert', 'vaccination_reminder', 'egg_collection', 'weather_alert', etc.
            $table->string('title');
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['unread', 'read', 'archived'])->default('unread');
            $table->json('data')->nullable(); // Données supplémentaires
            $table->timestamp('read_at')->nullable();
            $table->timestamp('scheduled_at')->nullable(); // Pour les notifications programmées
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('related_entity_id')->nullable(); // ID de l'entité liée
            $table->string('related_entity_type')->nullable(); // Type d'entité liée
            $table->timestamps();
            
            $table->index(['type', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
