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
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->unsignedBigInteger('category_id');
                $table->decimal('unit_price', 10, 2);
                $table->integer('stock_quantity')->default(0);
                $table->boolean('in_stock')->default(false);
                
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade');
                
                $table->timestamps();
               
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
