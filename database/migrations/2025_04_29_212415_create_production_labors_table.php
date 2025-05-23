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
        Schema::create('production_labors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')
                  ->constrained('productions')
                  ->onDelete('cascade');
            $table->unsignedInteger('workers_count');
            $table->unsignedDecimal('worker_price', 10, 2);
            $table->text('description')->nullable(); // NOUVEAU CHAMP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_labors');
    }
};
