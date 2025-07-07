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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('leave_type'); // Jenis izin: "izin", "sakit", "cuti"
            $table->date('start_date'); // Tanggal mulai izin
            $table->date('end_date'); // Tanggal selesai izin
            $table->integer('total_days'); // Total hari izin
            $table->text('reason'); // Alasan izin
            $table->string('status')->default('pending'); // Status: "pending", "approved", "rejected"
            $table->text('admin_notes')->nullable(); // Catatan admin
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Disetujui oleh
            $table->timestamp('approved_at')->nullable(); // Waktu persetujuan
            $table->string('attachment_path')->nullable(); // Path file attachment (surat dokter, dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
