<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_profile_id')->constrained('peserta_profiles')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('jenis', 16);
            $table->text('alasan');
            $table->string('bukti_path')->nullable();
            $table->string('status', 16)->default('pending');
            $table->foreignId('verified_by_pembimbing_id')->nullable()->constrained('pembimbing_profiles')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_pembimbing')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
