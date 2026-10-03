<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    Category::create([
        'name' => 'Workshop',
        'slug' => 'workshop',
    ]);

    Category::create([
        'name' => 'Seminar',
        'slug' => 'seminar',
    ]);

    Category::create([
        'name' => 'Praktikum',
        'slug' => 'praktikum',
    ]);
    }
}
