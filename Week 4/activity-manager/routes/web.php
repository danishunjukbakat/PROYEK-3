<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::post(
    'activities/{activity}/publish',
    [ActivityController::class, 'publish']
)->name('activities.publish');

Route::post(
    'activities/{activity}/complete',
    [ActivityController::class, 'complete']
)->name('activities.complete');

Route::patch(
    'activities/{activity}/restore',
    [ActivityController::class, 'restore']
)->name('activities.restore');
Route::resource('activities', ActivityController::class);
Route::resource('categories', CategoryController::class);

