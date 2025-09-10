<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'full', 'incremental', 'differential'
            $table->string('file_path');
            $table->bigInteger('file_size'); // Taille en bytes
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Métadonnées de la sauvegarde
            $table->timestamp('backup_date');
            $table->timestamp('expiry_date')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['type', 'status']);
            $table->index('backup_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backups');
    }
};
