<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
Route::get('/',fn()=>redirect()->route('barang.index'));
Route::get('/barang',[ShopController::class,'index'])->name('barang.index');
Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'create'])->name('login');
    Route::post('/login',[AuthController::class,'store'])->middleware('throttle:6,1')->name('login.store');
});
Route::middleware('auth')->group(function(){
    Route::post('/logout',[AuthController::class,'destroy'])->name('logout');
    Route::get('/keranjang',[ShopController::class,'cart'])->name('keranjang.index');
    Route::post('/keranjang',[ShopController::class,'add'])->name('keranjang.store');
    Route::patch('/keranjang/{id}',[ShopController::class,'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}',[ShopController::class,'remove'])->name('keranjang.destroy');
    Route::delete('/keranjang',[ShopController::class,'clear'])->name('keranjang.clear');
    Route::get('/checkout',[ShopController::class,'checkoutForm'])->name('checkout');
    Route::post('/checkout',[ShopController::class,'checkout'])->name('checkout.store');
    Route::get('/pesanan',[ShopController::class,'orders'])->name('pesanan.index');
    Route::get('/pesanan/{id}',[ShopController::class,'show'])->name('pesanan.show');
});
