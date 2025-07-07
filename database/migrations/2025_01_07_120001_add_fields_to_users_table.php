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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'bendahara', 'guru'])->default('guru')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->boolean('password_changed')->default(false)->after('is_active');
            $table->timestamp('first_login_at')->nullable()->after('password_changed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'password_changed', 'first_login_at']);
        });
    }
};
