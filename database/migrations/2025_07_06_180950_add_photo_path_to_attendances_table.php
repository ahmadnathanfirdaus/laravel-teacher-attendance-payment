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
            $table->string('photo_masuk')->nullable()->after('keterangan');
            $table->string('photo_keluar')->nullable()->after('photo_masuk');
            $table->string('location')->nullable()->after('photo_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['photo_masuk', 'photo_keluar', 'location']);
        });
    }
};
