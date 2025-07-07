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
        // 1. Create teacher_positions pivot table for multiple positions
        Schema::create('teacher_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'position_id']);
        });

        // 2. Add calculation type to allowance_types (per hari, per bulan)
        Schema::table('allowance_types', function (Blueprint $table) {
            $table->enum('calculation_type', ['fixed', 'per_hari', 'per_bulan'])->default('fixed')->after('default_amount');
        });

        // 3. Rename gaji_pokok to nominal and add salary type in teachers table
        Schema::table('teachers', function (Blueprint $table) {
            $table->renameColumn('gaji_pokok', 'nominal');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->enum('salary_type', ['per_hari', 'per_jam', 'per_bulan'])->default('per_bulan')->after('nominal');
        });

        // 4. Add calculation type to teacher_allowances
        Schema::table('teacher_allowances', function (Blueprint $table) {
            $table->enum('calculation_type', ['fixed', 'per_hari', 'per_bulan'])->default('fixed')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes
        Schema::dropIfExists('teacher_positions');

        Schema::table('allowance_types', function (Blueprint $table) {
            $table->dropColumn('calculation_type');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('salary_type');
            $table->renameColumn('nominal', 'gaji_pokok');
        });

        Schema::table('teacher_allowances', function (Blueprint $table) {
            $table->dropColumn('calculation_type');
        });
    }
};
