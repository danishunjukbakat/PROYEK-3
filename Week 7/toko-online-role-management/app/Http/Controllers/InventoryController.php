<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() { return view('inventory', ['products' => Product::orderBy('id_barang')->get()]); }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:50'],
            'deskripsi' => ['required', 'string', 'max:2000'],
            'harga' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
            'stok' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ]);
        $product->update($data);
        return back()->with('success', 'Produk diperbarui.');
    }
}
