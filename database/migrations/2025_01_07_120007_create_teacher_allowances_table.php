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
        Schema::create('teacher_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('allowance_type_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2); // Nominal tunjangan untuk guru ini (bisa di-override)
            $table->enum('calculation_type', ['per_hari', 'per_bulan'])->default('per_hari'); // Tipe perhitungan (bisa di-override)
            $table->date('effective_date'); // Tanggal mulai berlaku
            $table->date('end_date')->nullable(); // Tanggal berakhir (nullable untuk tunjangan aktif)
            $table->text('notes')->nullable(); // Catatan terkait tunjangan
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['teacher_id', 'allowance_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_allowances');
    }
};
