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
        Schema::create('invitation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('role')->default('user');
            $table->boolean('is_used')->default(false);
            $table->datetime('expires_at');
            $table->foreignId('created_by')->nullable()->constrained('users');

            // Ajout direct de used_by et used_at
            $table->foreignId('used_by')->nullable()->constrained('users');
            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_codes');
    }
};
