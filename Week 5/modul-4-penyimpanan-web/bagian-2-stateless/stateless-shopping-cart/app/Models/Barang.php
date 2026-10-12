<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Barang extends Model {
    protected $table='barang';
    public $timestamps=false;
    protected $fillable=['id','nama','harga','stok'];
    protected function casts(): array {return ['harga'=>'decimal:2','stok'=>'integer'];}
}
