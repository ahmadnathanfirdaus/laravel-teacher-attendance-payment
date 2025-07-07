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
            $table->decimal('amount', 12, 2); // Nominal tunjangan untuk guru tertentu
            $table->date('effective_date'); // Tanggal berlaku
            $table->date('end_date')->nullable(); // Tanggal berakhir (jika ada)
            $table->text('notes')->nullable(); // Catatan
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk performa
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
