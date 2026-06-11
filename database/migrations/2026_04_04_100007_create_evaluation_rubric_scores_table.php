<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_rubric_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('rubric_id')->constrained('rubrics')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->timestamps();

            $table->unique(['evaluation_id', 'rubric_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_rubric_scores');
    }
};
