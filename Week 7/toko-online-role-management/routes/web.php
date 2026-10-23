<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
Route::get('/',fn()=>redirect()->route('barang.index'));
Route::get('/barang',[ShopController::class,'index'])->name('barang.index');
Route::middleware('guest')->group(function(){
    Route::get('/register',[AuthController::class,'registration'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->middleware('throttle:6,1')->name('register.store');
    Route::get('/login',[AuthController::class,'create'])->name('login');
    Route::post('/login',[AuthController::class,'store'])->middleware('throttle:6,1')->name('login.store');
});
Route::middleware('auth')->group(function(){
    Route::post('/logout',[AuthController::class,'destroy'])->name('logout');
    Route::middleware('can:use-cart')->group(function () {
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
});

Route::get('/dashboard', fn () => view('dashboard'))->middleware('auth')->name('dashboard');
// Experiment UI is available only in local/testing, never production.
if (app()->environment(['local', 'testing'])) {
    Route::view('/lab', 'lab')->middleware('auth')->name('lab');
}
Route::get('/stateless-shop', [\App\Http\Controllers\StatelessCartController::class, 'index'])->name('stateless.shop');
Route::middleware(['auth', 'can:use-cart'])->group(function () {
    Route::post('/cart/add', [\App\Http\Controllers\StatelessCartController::class, 'add'])->name('stateless.cart.add');
    Route::post('/cart/update', [\App\Http\Controllers\StatelessCartController::class, 'update'])->name('stateless.cart.update');
});
Route::middleware(['auth', 'can:manage-roles'])->group(function () {
    Route::get('/admin/users', [\App\Http\Controllers\RoleController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/role', [\App\Http\Controllers\RoleController::class, 'update'])->name('admin.users.role.update');
});
Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'index'])->middleware(['auth','can:adjust-stock'])->name('inventory');
Route::patch('/admin/products/{product}', [\App\Http\Controllers\InventoryController::class, 'update'])->middleware(['auth','can:manage-products'])->name('admin.products.update');
