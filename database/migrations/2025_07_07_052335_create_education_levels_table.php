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
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // MI, MTs, MA, SMP, SMA, dll
            $table->string('full_name'); // Madrasah Ibtidaiyah, Madrasah Tsanawiyah, dll
            $table->text('description')->nullable();
            $table->integer('level_order')->default(1); // Urutan jenjang (1=MI, 2=MTs, 3=MA, dll)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
            $table->index(['level_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};
