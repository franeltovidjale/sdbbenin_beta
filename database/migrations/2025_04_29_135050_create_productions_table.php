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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('production_name');
            $table->foreignId('type_id')
                  ->constrained('type_productions')
                  ->onDelete('cascade');
            $table->string('production_date');
            $table->unsignedDecimal('qte_production', 10, 2);
            $table->enum('status', ['en cours', 'terminé'])->default('en cours');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
