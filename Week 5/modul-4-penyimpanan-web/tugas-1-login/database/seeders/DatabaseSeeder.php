<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::firstOrCreate(['username'=>'budi'],['nama_lengkap'=>'Budi Santoso','password'=>'rahasia123']);
        User::firstOrCreate(['username'=>'sari'],['nama_lengkap'=>'Sari Wulandari','password'=>'belajar123']);
    }
}
