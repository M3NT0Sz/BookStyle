<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $books = Book::all()->where('user_id', auth()->user()->id);
        return view('user.profile', ['books' => $books, 'user' => $user]);
    }

    public function update(Request $request, User $user){
        $user->update($request->all());
        return redirect()->route('user.profile');
    }
}
