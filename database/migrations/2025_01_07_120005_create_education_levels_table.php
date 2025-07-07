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
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama jenjang: "MI", "MTs", "MA", "D3", "S1", "S2", "S3"
            $table->string('full_name'); // Nama lengkap: "Madrasah Ibtidaiyah", etc.
            $table->text('description')->nullable(); // Deskripsi jenjang
            $table->integer('level_order')->default(1); // Urutan level (untuk sorting)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
            $table->index(['level_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};
