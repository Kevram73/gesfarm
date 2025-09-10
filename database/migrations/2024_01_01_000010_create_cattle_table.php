<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cattle', function (Blueprint $table) {
            $table->id();
            $table->string('tag_number')->unique();
            $table->string('name')->nullable();
            $table->string('breed');
            $table->string('gender'); // male, female
            $table->date('birth_date');
            $table->string('mother_tag')->nullable();
            $table->string('father_tag')->nullable();
            $table->decimal('current_weight', 8, 2)->nullable();
            $table->string('status')->default('active'); // active, sold, deceased
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cattle');
    }
};
