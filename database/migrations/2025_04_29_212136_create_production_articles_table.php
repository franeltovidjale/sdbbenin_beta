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
        Schema::create('production_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')
                  ->constrained('productions')
                  ->onDelete('cascade');
            $table->foreignId('article_id')
                  ->constrained('articles')
                  ->onDelete('cascade');
            $table->unsignedDecimal('quantity', 10, 2);
            $table->unsignedDecimal('unit_price', 10, 2);
            $table->unique(['production_id', 'article_id'], 'unique_production_article');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_articles');
    }
};
