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
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null');
            $table->time('expected_time_in')->nullable(); // Jam masuk yang diharapkan sesuai shift
            $table->time('expected_time_out')->nullable(); // Jam keluar yang diharapkan sesuai shift
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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn([
                'shift_id',
                'expected_time_in',
                'expected_time_out',
                'is_late',
                'late_minutes',
                'is_early_leave',
                'early_leave_minutes'
            ]);
        });
    }
};
