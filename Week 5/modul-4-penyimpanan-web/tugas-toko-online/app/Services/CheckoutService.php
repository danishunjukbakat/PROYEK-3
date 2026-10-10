<?php
namespace App\Services;
use App\Models\User;
use App\Models\Product;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CheckoutService {
    public function checkout(string $userId,string $address): string {
        return DB::transaction(function() use($userId,$address){
            // Request ganda untuk satu akun menunggu; request berikutnya akan membaca cart kosong.
            User::whereKey($userId)->lockForUpdate()->firstOrFail();
            $cart=DB::table('cart_items')->where('id_user',$userId)->orderBy('id_barang')->lockForUpdate()->get();
            if($cart->isEmpty()) $this->fail('Keranjang masih kosong.');
            $products=Product::whereIn('id_barang',$cart->pluck('id_barang'))->orderBy('id_barang')->lockForUpdate()->get()->keyBy('id_barang');
            $total=0;$details=[];
            foreach($cart as $item){
                $product=$products->get($item->id_barang);$qty=(int)$item->jumlah_beli;
                if(!$product || $qty<1) $this->fail('Ada barang atau jumlah yang tidak valid.');
                if($qty>$product->stok) $this->fail('Stok '.$product->nama_barang.' tidak cukup. Perbarui keranjang.');
                $price=Money::cents($product->harga);
                if($price>0 && $qty>intdiv(Money::MAX-$total,$price)) $this->fail('Total pesanan melebihi batas.');
                $total+=$price*$qty;
                $details[]=['id_barang'=>$product->id_barang,'harga_satuan'=>Money::decimal($price),'jumlah_beli'=>$qty];
            }
            do {$orderId='ORD'.strtoupper(bin2hex(random_bytes(6)));} while(DB::table('orders')->where('id_order',$orderId)->exists());
            DB::table('orders')->insert(['id_order'=>$orderId,'id_user'=>$userId,'tanggal_order'=>now(),'total_harga'=>Money::decimal($total),'alamat_pengiriman'=>$address]);
            foreach($details as $detail){
                DB::table('order_details')->insert(['id_order'=>$orderId]+$detail);
                Product::whereKey($detail['id_barang'])->decrement('stok',$detail['jumlah_beli']);
            }
            DB::table('cart_items')->where('id_user',$userId)->delete();
            return $orderId;
        },3);
    }
    private function fail(string $message): never { throw ValidationException::withMessages(['checkout'=>$message]); }
}
