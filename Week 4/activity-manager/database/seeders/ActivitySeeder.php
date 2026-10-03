<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Category;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $workshop = Category::where('slug', 'workshop')->first();
        $seminar = Category::where('slug', 'seminar')->first();
        $praktikum = Category::where('slug', 'praktikum')->first();

        Activity::create([
            'category_id' => $workshop->id,
            'code' => 'ACT-001',
            'title' => 'Workshop Git Dasar',
            'description' => 'Latihan kolaborasi repository.',
            'start_at' => '2026-10-05 09:00:00',
            'end_at' => '2026-10-05 12:00:00',
            'location' => 'Lab Komputer',
            'capacity' => 30,
            'status' => 'draft',
        ]);

        Activity::create([
            'category_id' => $seminar->id,
            'code' => 'ACT-002',
            'title' => 'Seminar Web Quality',
            'description' => 'Pengenalan maintainability dan testing.',
            'start_at' => '2026-10-12 09:00:00',
            'end_at' => '2026-10-12 12:00:00',
            'location' => 'Aula Polban',
            'capacity' => 100,
            'status' => 'draft',
        ]);

        Activity::create([
            'category_id' => $praktikum->id,
            'code' => 'ACT-003',
            'title' => 'Praktikum Laravel',
            'description' => 'Membangun aplikasi Activity Manager.',
            'start_at' => '2026-10-15 13:00:00',
            'end_at' => '2026-10-15 16:00:00',
            'location' => 'Lab JTK',
            'capacity' => 30,
            'status' => 'draft',
        ]);
    }
}