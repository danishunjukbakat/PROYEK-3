<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
    protected $primaryKey='id_order';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps=false;
    protected $fillable=['id_order','id_user','tanggal_order','total_harga','alamat_pengiriman'];
    protected function casts(): array { return ['total_harga'=>'decimal:2','tanggal_order'=>'datetime']; }
}
