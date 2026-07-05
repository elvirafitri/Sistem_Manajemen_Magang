<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RubricSeeder::class,
            AdminUserSeeder::class,
            EvaluationSeeder::class,
            AttendanceSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
