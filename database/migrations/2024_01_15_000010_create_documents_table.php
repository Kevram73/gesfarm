<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type'); // 'pdf', 'jpg', 'png', 'doc', 'xlsx', etc.
            $table->string('mime_type');
            $table->bigInteger('file_size'); // Taille en bytes
            $table->string('category'); // 'invoice', 'certificate', 'photo', 'report', etc.
            $table->text('description')->nullable();
            $table->json('tags')->nullable(); // Tags pour la recherche
            $table->foreignId('related_entity_id')->nullable(); // ID de l'entité liée
            $table->string('related_entity_type')->nullable(); // Type d'entité liée
            $table->boolean('is_public')->default(false);
            $table->date('expiry_date')->nullable(); // Date d'expiration
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['category', 'related_entity_type']);
            $table->index('expiry_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
