<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {Schema::create('barang',function(Blueprint $t){$t->id();$t->string('nama',100);$t->decimal('harga',12,2);$t->unsignedInteger('stok');});}
    public function down(): void {Schema::dropIfExists('barang');}
};
