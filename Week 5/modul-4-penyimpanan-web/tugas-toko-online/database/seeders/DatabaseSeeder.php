<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::firstOrCreate(['id_user'=>'USR001'],['username'=>'budi','nama_lengkap'=>'Budi Santoso','email'=>'budi@example.test','password'=>'rahasia123','no_hp'=>'080000000001','alamat'=>'Jl. Contoh No. 1, Bandung']);
        User::firstOrCreate(['id_user'=>'USR002'],['username'=>'sari','nama_lengkap'=>'Sari Wulandari','email'=>'sari@example.test','password'=>'belajar123','no_hp'=>'080000000002','alamat'=>'Jl. Contoh No. 2, Bandung']);
        $items=[['Buku Tulis','5000.00',20],['Pulpen','3000.00',30],['Penggaris','4000.00',15],['Pensil 2B','2500.00',25],['Penghapus','1500.00',10],['Spidol','8000.00',12],['Map Plastik','3500.00',18],['Stabilo','7000.00',8],['Lem Kertas','6000.00',9],['Gunting','12000.00',0]];
        foreach($items as $i=>[$name,$price,$stock]){
            $id='BRG'.str_pad((string)($i+1),3,'0',STR_PAD_LEFT);
            Product::firstOrCreate(['id_barang'=>$id],['nama_barang'=>$name,'deskripsi'=>$name.' untuk kebutuhan belajar sehari-hari.','harga'=>$price,'stok'=>$stock,'gambar'=>$id.'.svg']);
        }
    }
}
