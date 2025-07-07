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
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->string('photo_path')->nullable(); // Path file foto guru
            $table->json('working_days')->nullable(); // Hari kerja guru (JSON array)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn(['position_id', 'photo_path', 'working_days']);
        });
    }
};
