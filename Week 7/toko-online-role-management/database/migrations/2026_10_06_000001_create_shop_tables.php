<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('users',function(Blueprint $t){
            $t->string('id_user',15)->primary();$t->string('nama_lengkap',100);
            $t->string('email',100)->unique();$t->string('username',50)->unique();
            $t->string('password',255);$t->string('no_hp',15);$t->text('alamat');
        });
        Schema::create('products',function(Blueprint $t){
            $t->string('id_barang',10)->primary();$t->string('nama_barang',50);$t->text('deskripsi');
            $t->decimal('harga',12,2);$t->unsignedInteger('stok');$t->string('gambar',255);
        });
        Schema::create('orders',function(Blueprint $t){
            $t->string('id_order',15)->primary();$t->string('id_user',15);$t->dateTime('tanggal_order');
            $t->decimal('total_harga',12,2);$t->text('alamat_pengiriman');
            $t->foreign('id_user')->references('id_user')->on('users')->restrictOnDelete();
        });
        Schema::create('order_details',function(Blueprint $t){
            $t->string('id_order',15);$t->string('id_barang',10);$t->decimal('harga_satuan',12,2);$t->unsignedInteger('jumlah_beli');
            $t->primary(['id_order','id_barang']);
            $t->foreign('id_order')->references('id_order')->on('orders')->restrictOnDelete();
            $t->foreign('id_barang')->references('id_barang')->on('products')->restrictOnDelete();
        });
        Schema::create('cart_items',function(Blueprint $t){
            $t->string('id_user',15);$t->string('id_barang',10);$t->unsignedInteger('jumlah_beli');
            $t->primary(['id_user','id_barang']);
            $t->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $t->foreign('id_barang')->references('id_barang')->on('products')->restrictOnDelete();
        });
    }
    public function down(): void {
        foreach(['cart_items','order_details','orders','products','users'] as $name) Schema::dropIfExists($name);
    }
};
