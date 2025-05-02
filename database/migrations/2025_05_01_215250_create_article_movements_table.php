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
        Schema::create('article_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->enum('type', ['entrée', 'sortie']);
            $table->decimal('quantity', 10, 2)->unsigned();
            $table->decimal('unit_price', 10, 2)->unsigned();
            $table->text('notes')->nullable();
            $table->enum('status', ['en attente', 'validé', 'rejeté'])->default('en attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_movements');
    }
};
