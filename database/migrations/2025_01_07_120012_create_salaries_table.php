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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('bulan'); // Bulan gaji
            $table->year('tahun'); // Tahun gaji
            $table->decimal('gaji_pokok', 12, 2); // Gaji pokok
            $table->decimal('tunjangan', 12, 2)->default(0); // Total tunjangan
            $table->decimal('bonus', 12, 2)->default(0); // Bonus
            $table->decimal('potongan', 12, 2)->default(0); // Total potongan
            $table->integer('hari_kerja'); // Total hari kerja dalam bulan
            $table->integer('hari_hadir'); // Total hari hadir
            $table->integer('hari_tidak_hadir'); // Total hari tidak hadir
            $table->decimal('total_gaji', 12, 2); // Total gaji bersih
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft'); // Status gaji
            $table->text('keterangan')->nullable(); // Catatan gaji
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
