<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $books = Book::paginate(8);
    return view('welcome', ['books'=> $books]);
})->name('index');

Route::get('/books/index', [BookController::class, 'index'])->name('books.index');
Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/show/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/edit/{book}', [BookController::class, 'edit'])->name('books.edit');
Route::post('/books/edit/{book}', [BookController::class, 'update'])->name('books.update');
Route::post('/books/create', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/index/{book}', [BookController::class, 'destroy'])->name('books.destroy');