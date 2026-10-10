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
