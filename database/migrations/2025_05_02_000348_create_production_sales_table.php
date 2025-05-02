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
        Schema::create('production_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                  ->constrained('type_productions')
                  ->onDelete('cascade');
            $table->enum('carton_type', ['petit', 'grand']);
            $table->decimal('quantity', 10, 2)->unsigned();
            $table->decimal('unit_price', 10, 2)->unsigned();
            $table->string('client_name');
            $table->string('client_firstname');
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();
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
        Schema::dropIfExists('production_sales');
    }
};
