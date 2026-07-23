<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {

            $table->boolean('is_terlambat')
                ->default(false)
                ->after('status');

            $table->integer('durasi_kerja')
                ->nullable()
                ->after('is_terlambat');

            $table->boolean('is_checkout_otomatis')
                ->default(false)
                ->after('durasi_kerja');
        });
    }


    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {

            $table->dropColumn([
                'is_terlambat',
                'durasi_kerja',
                'is_checkout_otomatis'
            ]);
        });
    }
};
