<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('pembimbing_id')->nullable()->constrained('pembimbing_profiles')->nullOnDelete();
            $table->string('nim', 32);
            $table->string('jurusan', 128)->nullable();
            $table->string('institusi', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->text('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_profiles');
    }
};
