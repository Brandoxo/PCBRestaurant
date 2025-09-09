<?php

use App\Http\Controllers\ProfileController;
use Aoo\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
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
Route::get('/Categories/Create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('Categories/Create') ->middleware(['auth', 'verified']);
Route::post('/Categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('Categories/Store') ->middleware(['auth', 'verified']);
Route::put('/Categories/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('Categories/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Categories/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('Categories/Delete') ->middleware(['auth', 'verified']);

//Tables
Route::get('/Tables', [\App\Http\Controllers\TableController::class, 'index'])->name('Tables/Index') ->middleware(['auth', 'verified']);
Route::get('/Tables/Create', [\App\Http\Controllers\TableController::class, 'create'])->name('Tables/Create') ->middleware(['auth', 'verified']);
Route::post('/Tables', [\App\Http\Controllers\TableController::class, 'store'])->name('Tables/Store') ->middleware(['auth', 'verified']);
Route::put('/Tables/edit/{id}', [\App\Http\Controllers\TableController::class, 'update'])->name('Tables/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Tables/{id}', [\App\Http\Controllers\TableController::class, 'destroy'])->name('Tables/Delete') ->middleware(['auth', 'verified']);

//Menu
Route::get('/Menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('Menu/Index') ->middleware(['auth', 'verified']);
Route::get('/Menu/Create', [\App\Http\Controllers\MenuController::class, 'create'])->name('Menu/Create') ->middleware(['auth', 'verified']);
Route::post('/Menu', [\App\Http\Controllers\MenuController::class, 'store'])->name('Menu/Store') ->middleware(['auth', 'verified']);
Route::post('/Menu/edit/{id}', [\App\Http\Controllers\MenuController::class, 'update'])->name('Menu/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Menu/{id}', [\App\Http\Controllers\MenuController::class, 'destroy'])->name('Menu/Delete') ->middleware(['auth', 'verified']);

// Products
Route::get('/Products/get', [\App\Http\Controllers\Products\ProductController::class, 'index'])->name('products.get') ->middleware(['auth', 'verified']);

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
