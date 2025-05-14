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

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile', 'public');
            $data['image'] = $imagePath;
        }

        $user->update($data);

        return redirect()->route('user.profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
