<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ShopController extends Controller {
    public function index(){return view('barang',['products'=>Product::orderBy('id_barang')->get()]);}
    public function cart(Request $request,CartService $cart){return view('keranjang',$cart->summary((string)$request->user()->getAuthIdentifier()));}
    public function add(Request $request,CartService $cart){
        $data=$request->validate(['id_barang'=>['required','string','max:10','exists:products,id_barang']]);
        $cart->add((string)$request->user()->getAuthIdentifier(),$data['id_barang']);
        return back()->with('success','Barang ditambahkan ke keranjang.');
    }
    public function update(Request $request,string $id,CartService $cart){
        $data=$request->validate(['jumlah'=>['required','integer','min:0','max:2147483647']]);
        $cart->update((string)$request->user()->getAuthIdentifier(),$id,(int)$data['jumlah']);
        return back()->with('success','Jumlah diperbarui.');
    }
    public function remove(Request $request,string $id,CartService $cart){
        $cart->remove((string)$request->user()->getAuthIdentifier(),$id);return back()->with('success','Barang dihapus.');
    }
    public function clear(Request $request,CartService $cart){
        $cart->clear((string)$request->user()->getAuthIdentifier());return back()->with('success','Keranjang dikosongkan.');
    }
    public function checkoutForm(Request $request,CartService $cart){
        $summary=$cart->summary((string)$request->user()->getAuthIdentifier());
        if($summary['rows']->isEmpty()) return redirect()->route('keranjang.index')->withErrors(['checkout'=>'Keranjang masih kosong.']);
        return view('checkout',$summary);
    }
    public function checkout(Request $request,CheckoutService $service){
        $data=$request->validate(['alamat_pengiriman'=>['required','string','min:5','max:2000']]);
        // Harga, total, dan id_user kiriman browser sengaja tidak digunakan.
        $id=$service->checkout((string)$request->user()->getAuthIdentifier(),$data['alamat_pengiriman']);
        return redirect()->route('pesanan.show',$id)->with('success','Checkout berhasil. Keranjang telah dikosongkan.');
    }
    public function orders(Request $request){
        $orders=Order::where('id_user',$request->user()->getAuthIdentifier())->orderByDesc('tanggal_order')->orderByDesc('id_order')->get();
        return view('pesanan.index',compact('orders'));
    }
    public function show(Request $request,string $id){
        // Scope kepemilikan dilakukan sebelum membaca detail. Akun lain mendapat 404.
        $order=Order::where('id_user',$request->user()->getAuthIdentifier())->whereKey($id)->firstOrFail();
        $details=DB::table('order_details as d')->join('products as p','p.id_barang','=','d.id_barang')->where('d.id_order',$id)->select('d.*','p.nama_barang')->orderBy('d.id_barang')->get();
        return view('pesanan.show',compact('order','details'));
    }
}
