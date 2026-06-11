<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_profile_id')->constrained('peserta_profiles')->cascadeOnDelete();
            $table->date('tanggal');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->string('status', 16)->default('alpa');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['peserta_profile_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
