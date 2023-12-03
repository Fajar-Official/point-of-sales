<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('api/categories', [CategoryController::class, 'api'])->name('categories.api');
    Route::resource('categories', CategoryController::class);

    Route::get('api/products', [ProductController::class, 'api'])->name('products.api');
    Route::resource('products', ProductController::class);

    Route::get('api/members', [MemberController::class, 'api'])->name('members.api');
    Route::resource('members', MemberController::class);
});


require __DIR__ . '/auth.php';
