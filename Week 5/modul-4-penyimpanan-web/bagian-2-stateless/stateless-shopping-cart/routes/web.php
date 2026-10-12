<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
Route::get('/',fn()=>redirect()->route('barang.index'));
Route::get('/barang',[CartController::class,'products'])->name('barang.index');
Route::get('/keranjang',[CartController::class,'index'])->name('keranjang.index');
Route::post('/keranjang',[CartController::class,'store'])->block(10,10)->name('keranjang.store');
Route::patch('/keranjang/{id}',[CartController::class,'update'])->whereNumber('id')->block(10,10)->name('keranjang.update');
Route::delete('/keranjang/{id}',[CartController::class,'destroy'])->whereNumber('id')->block(10,10)->name('keranjang.destroy');
Route::delete('/keranjang',[CartController::class,'clear'])->block(10,10)->name('keranjang.clear');

// Modul 4 Bagian 2: state keranjang selalu dibaca dari payload request.
Route::get('/stateless-shop', [\App\Http\Controllers\StatelessCartController::class, 'index'])->name('stateless.shop');
Route::post('/cart/add', [\App\Http\Controllers\StatelessCartController::class, 'add'])->name('stateless.cart.add');
Route::post('/cart/update', [\App\Http\Controllers\StatelessCartController::class, 'update'])->name('stateless.cart.update');
