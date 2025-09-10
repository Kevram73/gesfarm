<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('veterinary_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('treatment_type'); // 'vaccination', 'medication', 'surgery', 'checkup', etc.
            $table->string('treatment_name');
            $table->text('description')->nullable();
            $table->date('treatment_date');
            $table->time('treatment_time')->nullable();
            $table->string('animal_type'); // 'poultry', 'cattle', 'sheep', 'goat', etc.
            $table->foreignId('animal_id')->nullable(); // ID de l'animal spécifique
            $table->string('animal_identifier')->nullable(); // Numéro d'identification
            $table->string('veterinarian_name')->nullable();
            $table->string('veterinarian_license')->nullable();
            $table->json('medications')->nullable(); // Liste des médicaments
            $table->json('dosages')->nullable(); // Dosages administrés
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('next_treatment_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // Photos, documents
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['treatment_type', 'treatment_date']);
            $table->index(['animal_type', 'animal_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('veterinary_treatments');
    }
};
