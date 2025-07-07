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
        Schema::create('allowance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama jenis tunjangan: "Transportasi", "Makan", "Komunikasi", "Jabatan"
            $table->text('description')->nullable(); // Deskripsi jenis tunjangan
            $table->decimal('default_amount', 12, 2)->default(0); // Nominal default
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_types');
    }
};
