<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class CartController extends Controller {
    public function products() { return view('barang',['barang'=>Barang::orderBy('id')->get()]); }
    public function index(Request $request) {
        $cart=$request->session()->get('keranjang',[]);
        $products=Barang::whereIn('id',array_keys($cart))->get()->keyBy('id');
        $rows=[];$total=0;
        foreach($cart as $id=>$jumlah) {
            $product=$products->get($id);
            $subtotal=$product ? Money::cents($product->harga)*$jumlah : 0;
            $rows[]=compact('id','product','jumlah','subtotal');$total+=$subtotal;
        }
        return view('keranjang',compact('rows','total'));
    }
    public function store(Request $request) {
        $data=$request->validate(['id'=>['required','integer','exists:barang,id']]);
        $product=Barang::findOrFail($data['id']);
        $cart=$request->session()->get('keranjang',[]);
        $jumlah=($cart[$product->id]??0)+1;
        $this->checkStock($jumlah,$product->stok);
        $cart[$product->id]=$jumlah;
        $request->session()->put('keranjang',$cart);
        return back()->with('success','Barang ditambahkan ke keranjang.');
    }
    public function update(Request $request,int $id) {
        $data=$request->validate(['jumlah'=>['required','integer','min:0','max:2147483647']]);
        $cart=$request->session()->get('keranjang',[]);
        if(!array_key_exists($id,$cart)) abort(404);
        $jumlah=(int)$data['jumlah'];
        if($jumlah===0) unset($cart[$id]);
        else {
            $product=Barang::findOrFail($id);
            $this->checkStock($jumlah,$product->stok);
            $cart[$id]=$jumlah;
        }
        $request->session()->put('keranjang',$cart);
        return back()->with('success','Jumlah diperbarui.');
    }
    public function destroy(Request $request,int $id) {
        $cart=$request->session()->get('keranjang',[]);unset($cart[$id]);
        $request->session()->put('keranjang',$cart);
        return back()->with('success','Item dihapus.');
    }
    public function clear(Request $request) {
        $request->session()->forget('keranjang');
        return back()->with('success','Keranjang dikosongkan.');
    }
    private function checkStock(int $jumlah,int $stok): void {
        if($jumlah>$stok) throw ValidationException::withMessages(['jumlah'=>"Jumlah tidak boleh melebihi stok ($stok)."]);
    }
}
