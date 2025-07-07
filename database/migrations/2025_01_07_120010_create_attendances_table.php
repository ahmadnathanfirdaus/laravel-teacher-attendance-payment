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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->date('tanggal'); // Tanggal absensi
            $table->time('jam_masuk')->nullable(); // Jam masuk
            $table->time('jam_keluar')->nullable(); // Jam keluar
            $table->enum('status', ['hadir', 'tidak_hadir', 'terlambat', 'izin', 'sakit'])->default('tidak_hadir'); // Status kehadiran
            $table->text('keterangan')->nullable(); // Catatan (alasan izin/sakit, dll)
            $table->string('photo_masuk')->nullable(); // Path foto selfie saat masuk
            $table->string('photo_keluar')->nullable(); // Path foto selfie saat keluar
            $table->timestamps();
            $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null');
            $table->time('expected_time_in')->nullable(); // Jam masuk yang diharapkan
            $table->time('expected_time_out')->nullable(); // Jam keluar yang diharapkan
            $table->boolean('is_late')->default(false); // Apakah terlambat
            $table->integer('late_minutes')->default(0); // Menit keterlambatan
            $table->boolean('is_early_leave')->default(false); // Apakah pulang lebih awal
            $table->integer('early_leave_minutes')->default(0); // Menit pulang lebih awal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
