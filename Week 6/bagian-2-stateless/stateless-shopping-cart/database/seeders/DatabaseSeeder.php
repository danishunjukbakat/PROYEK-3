<?php
namespace Database\Seeders;
use App\Models\Barang;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        foreach([[1,'Buku Tulis','5000.00',20],[2,'Pulpen','3000.00',30],[3,'Penggaris','4000.00',15],[4,'Pensil 2B','2500.00',25],[5,'Penghapus','1500.00',10]] as [$id,$nama,$harga,$stok]) {
            Barang::firstOrCreate(['id'=>$id],compact('nama','harga','stok'));
        }
    }
}
