<?php

use App\Http\Controllers\ProfileController;
use Aoo\Http\Controllers\OrderController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Index');
})->middleware(['auth', 'verified'])->name('dashboard');



//Categories
Route::get('/Categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('Categories/Index') ->middleware(['auth', 'verified']);
Route::get('/Categories/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('Categories/Create') ->middleware(['auth', 'verified']);
Route::post('/Categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('Categories/Store') ->middleware(['auth', 'verified']);
Route::get('/Categories/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('Categories/Edit') ->middleware(['auth', 'verified']);
Route::put('/Categories/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('Categories/Update') ->middleware(['auth', 'verified']);
Route::delete('/Categories/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('Categories/Delete') ->middleware(['auth', 'verified']);

//Orders
Route::get('/Orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('Orders/Index') ->middleware(['auth', 'verified']);

//Config
Route::get('/Config', [\App\Http\Controllers\ConfigController::class, 'index'])->name('Config/Index') ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
