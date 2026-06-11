<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_profile_id')->constrained('peserta_profiles')->cascadeOnDelete();
            $table->foreignId('pembimbing_profile_id')->nullable()->constrained('pembimbing_profiles')->nullOnDelete();
            $table->decimal('total_nilai', 5, 2)->nullable();
            $table->text('komentar_final')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->boolean('is_final')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
