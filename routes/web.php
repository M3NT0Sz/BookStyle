<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $booksNew = Book::where('condition', 'new')->paginate(8); // Livros novos
    $booksOld = Book::where('condition', 'used')->paginate(8); // Livros usados
    return view('welcome', compact('booksNew', 'booksOld'));
})->name('index');

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/books/index', [BookController::class, 'index'])->name('books.index');
Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/show/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/edit/{book}', [BookController::class, 'edit'])->name('books.edit');
Route::post('/books/edit/{book}', [BookController::class, 'update'])->name('books.update');
Route::post('/books/create', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/index/{book}', [BookController::class, 'destroy'])->name('books.destroy');