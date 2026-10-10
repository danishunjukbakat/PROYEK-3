<?php
namespace App\Services;
use App\Models\User;
use App\Models\Product;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CartService {
    public function summary(string $userId): array {
        $rows=DB::table('cart_items as c')->join('products as p','p.id_barang','=','c.id_barang')
            ->where('c.id_user',$userId)->select('p.*','c.jumlah_beli')->orderBy('p.id_barang')->get();
        $total=0;
        foreach($rows as $row){$row->subtotal=Money::cents((string)$row->harga)*(int)$row->jumlah_beli;$total+=$row->subtotal;}
        return compact('rows','total');
    }
    public function add(string $userId,string $id): void {
        DB::transaction(function() use($userId,$id){
            $this->lockUser($userId);
            $product=Product::whereKey($id)->lockForUpdate()->firstOrFail();
            $qty=(int)DB::table('cart_items')->where('id_user',$userId)->where('id_barang',$id)->value('jumlah_beli')+1;
            $this->checkStock($qty,$product->stok);
            DB::table('cart_items')->updateOrInsert(['id_user'=>$userId,'id_barang'=>$id],['jumlah_beli'=>$qty]);
        },3);
    }
    public function update(string $userId,string $id,int $qty): void {
        DB::transaction(function() use($userId,$id,$qty){
            $this->lockUser($userId);
            $item=DB::table('cart_items')->where('id_user',$userId)->where('id_barang',$id);
            if(!$item->exists()) abort(404);
            if($qty===0){$item->delete();return;}
            $product=Product::whereKey($id)->lockForUpdate()->firstOrFail();
            $this->checkStock($qty,$product->stok);
            $item->update(['jumlah_beli'=>$qty]);
        },3);
    }
    public function remove(string $userId,string $id): void {
        DB::transaction(function() use($userId,$id){
            $this->lockUser($userId);
            DB::table('cart_items')->where('id_user',$userId)->where('id_barang',$id)->delete();
        },3);
    }
    public function clear(string $userId): void {
        DB::transaction(function() use($userId){$this->lockUser($userId);DB::table('cart_items')->where('id_user',$userId)->delete();},3);
    }
    private function lockUser(string $userId): void {
        // Semua mutasi cart dan checkout memakai kunci akun yang sama.
        User::whereKey($userId)->lockForUpdate()->firstOrFail();
    }
    private function checkStock(int $qty,int $stock): void {
        if($qty<1 || $qty>$stock) throw ValidationException::withMessages(['jumlah'=>"Jumlah harus antara 1 dan stok tersedia ($stock)."]);
    }
}
