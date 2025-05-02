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
        Schema::create('production_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')
                  ->constrained('productions')
                  ->onDelete('cascade');
            $table->enum('carton_type', ['petit', 'grand']);
            $table->unsignedInteger('carton_count');
            $table->timestamps();
             // Ajouter une contrainte d'unicité sur la combinaison de production_id et carton_type
            $table->unique(['production_id', 'carton_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_outputs');
    }
};
