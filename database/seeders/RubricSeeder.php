<?php

namespace Database\Seeders;

use App\Models\Rubric;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubricSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['nama' => 'Disiplin', 'urutan' => 1, 'bobot_maks' => 20],
            ['nama' => 'Kerjasama', 'urutan' => 2, 'bobot_maks' => 20],
            ['nama' => 'Inisiatif dan Kreatif', 'urutan' => 3, 'bobot_maks' => 20],
            ['nama' => 'Tanggung Jawab', 'urutan' => 4, 'bobot_maks' => 20],
            ['nama' => 'Kerajinan', 'urutan' => 5, 'bobot_maks' => 20],
        ];

        $names = array_column($rows, 'nama');

        // Delete rubrics that are NOT in the allowed list
        Rubric::whereNotIn('nama', $names)->delete();

        foreach ($rows as $r) {
            Rubric::updateOrCreate(
                ['nama' => $r['nama']],
                $r
            );
        }
    }
}
