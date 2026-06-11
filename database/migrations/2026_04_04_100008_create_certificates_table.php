<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_profile_id')->unique()->constrained('peserta_profiles')->cascadeOnDelete();
            $table->string('nomor_sertifikat', 64)->unique();
            $table->string('file_path')->nullable();
            $table->decimal('kehadiran_persen', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
