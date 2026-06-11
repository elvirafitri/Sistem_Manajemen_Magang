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
        Schema::table('peserta_profiles', function (Blueprint $table) {
            $table->string('jenis_program')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_profiles', function (Blueprint $table) {
            // Revert back to enum if needed, though usually string is safer.
            $table->enum('jenis_program', ['magang', 'pkl'])->default('magang')->change();
        });
    }
};
