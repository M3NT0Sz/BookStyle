<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $books = Book::all()->where('user_id', auth()->user()->id);
        return view('user.profile', ['books' => $books, 'user' => $user]);
    }
}
