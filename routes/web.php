<?php


use Illuminate\Support\Facades\Route;
use App\Models\Book;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AdminController;

// Rotas de administrador (sem middleware admin para teste)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/books', [AdminController::class, 'books'])->name('admin.books');
    Route::get('/books/export/{format}', [AdminController::class, 'exportBooks'])->name('admin.books.export');
    Route::get('/books/{id}', [AdminController::class, 'showBook'])->name('admin.books.show');
    Route::delete('/books/{id}', [AdminController::class, 'destroyBook'])->name('admin.books.destroy');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/export/{format}', [AdminController::class, 'exportUsers'])->name('admin.users.export');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/coupons/export/{format}', [AdminController::class, 'exportCoupons'])->name('admin.coupons.export');
});

Route::get('/', function () {
    $allBooks = Book::getAllBooks();
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
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/update-quantity/{bookId}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
