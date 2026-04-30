<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WbsReport;
use Illuminate\Support\Str;

class WbsReportSeeder extends Seeder
{
    public function run(): void
    {
        // ambil user pelapor (kalau belum ada, buat)
        $user = User::firstOrCreate(
            ['email' => 'pelapor@test.com'],
            [
                'name' => 'Pelapor Dummy',
                'password' => bcrypt('password'),
                'role' => 'pelapor',
                'is_active' => true,
            ]
        );

        $categories = array_keys(WbsReport::categoryOptions());

        for ($i = 1; $i <= 5; $i++) {
            WbsReport::create([
                'report_number' => 'WBS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'category' => $categories[array_rand($categories)],
                'title' => 'Laporan Dummy #' . $i,
                'description' => 'Ini adalah contoh laporan pelanggaran yang digunakan untuk testing sistem WBS.',
                'involved_parties' => 'Oknum A, Oknum B',
                'location' => 'Area Operasional BSP',
                'incident_date' => now()->subDays(rand(1, 30)),
                'chronology' => 'Terjadi dugaan pelanggaran prosedur operasional yang menyebabkan kerugian perusahaan.',
                'estimated_loss' => rand(1000000, 50000000),
                'has_evidence' => true,
                'reported_before' => false,
                'reported_to_other_party' => false,
                'status' => collect(WbsReport::statusOptions())->keys()->random(),
                'submitted_at' => now(),
            ]);
        }
    }
}