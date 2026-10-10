<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::get('/',fn()=>redirect()->route('login'));
Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'create'])->name('login');
    Route::post('/login',[AuthController::class,'store'])->middleware('throttle:6,1')->name('login.store');
});
Route::middleware('auth')->group(function(){
    Route::view('/dashboard','dashboard')->name('dashboard');
    Route::post('/logout',[AuthController::class,'destroy'])->name('logout');
});
