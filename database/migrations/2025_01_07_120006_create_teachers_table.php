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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nip')->unique(); // Nomor Induk Pegawai
            $table->text('alamat'); // Alamat lengkap
            $table->string('no_telepon'); // Nomor telepon
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']); // Jenis kelamin
            $table->date('tanggal_lahir'); // Tanggal lahir
            $table->string('tempat_lahir'); // Tempat lahir
            $table->string('pendidikan_terakhir'); // Pendidikan terakhir
            $table->string('mata_pelajaran'); // Mata pelajaran yang diajar
            $table->date('tanggal_masuk'); // Tanggal masuk kerja
            $table->decimal('nominal', 12, 2); // Gaji/nominal (bisa per hari/jam/bulan)
            $table->enum('salary_type', ['per_hari', 'per_jam', 'per_bulan'])->default('per_bulan'); // Tipe perhitungan gaji
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('photo_path')->nullable(); // Path foto profil
            $table->json('working_days')->nullable(); // Hari kerja (JSON array)
            $table->foreignId('education_level_id')->nullable()->constrained()->onDelete('set null'); // Jenjang pendidikan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
