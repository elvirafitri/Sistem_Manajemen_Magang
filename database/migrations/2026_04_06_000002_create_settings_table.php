<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('settings')->insert([
            ['key' => 'office_lat',            'value' => null,  'label' => 'Latitude Kantor',          'created_at' => now(), 'updated_at' => now()],
            ['key' => 'office_lng',            'value' => null,  'label' => 'Longitude Kantor',         'created_at' => now(), 'updated_at' => now()],
            ['key' => 'allowed_radius_meters', 'value' => '100', 'label' => 'Radius Toleransi (meter)', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
