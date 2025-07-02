<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $allBooks = Book::all();
    $booksNew = array_filter($allBooks, function($book) {
        return $book['condition'] === 'new';
    });
    $booksOld = array_filter($allBooks, function($book) {
        return $book['condition'] === 'used';
    });
    // Paginação manual (exemplo simples)
    $booksNew = array_slice($booksNew, 0, 8);
    $booksOld = array_slice($booksOld, 0, 8);
    return view('welcome', compact('booksNew', 'booksOld'));
})->name('index');

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/books/index', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/show/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/edit/{book}', [BookController::class, 'update'])->name('books.update');
Route::post('/books/create', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/index/{book}', [BookController::class, 'destroy'])->name('books.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/user/update/{user}', [UserController::class, 'update'])->name('user.update');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{bookId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{bookId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
Route::get('/coupons/{id}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');