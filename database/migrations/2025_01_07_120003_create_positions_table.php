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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama jabatan: "Kepala Sekolah", "Wakil Kepala Sekolah", "Guru Kelas", etc.
            $table->text('description')->nullable(); // Deskripsi jabatan
            $table->decimal('base_allowance', 12, 2)->default(0); // Tunjangan dasar jabatan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
