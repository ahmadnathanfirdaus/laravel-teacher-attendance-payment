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
        Schema::create('teacher_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->set('days', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']); // Hari kerja
            $table->date('effective_date'); // Tanggal berlaku
            $table->date('end_date')->nullable(); // Tanggal berakhir (jika ada)
            $table->text('notes')->nullable(); // Catatan
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk performa
            $table->index(['teacher_id', 'shift_id']);
            $table->unique(['teacher_id', 'shift_id', 'effective_date'], 'teacher_shift_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_shifts');
    }
};
