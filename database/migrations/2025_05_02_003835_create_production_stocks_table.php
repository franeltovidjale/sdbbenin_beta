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
        Schema::create('production_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                  ->constrained('type_productions')
                  ->onDelete('cascade');
            $table->enum('carton_type', ['petit', 'grand']);
            $table->decimal('quantity', 10, 2)->default(0);
            $table->timestamps();
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['type_id', 'carton_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_stocks');
    }
};
