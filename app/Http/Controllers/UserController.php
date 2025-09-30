<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        $userAuth = auth()->user();
        $user = User::find($userAuth->id);
        
        // Garante que o usuário tenha a propriedade image
        if (!isset($user['image']) || empty($user['image'])) {
            $user['image'] = 'perfil.png';
        }
        
        $allBooks = Book::getAllBooks();
        $books = array_filter($allBooks, function($book) use ($user) {
            return $book['user_id'] == $user['id'];
        });
        // Garante que $user e $books sejam objetos para a view
        return view('user.profile', [
            'books' => array_map(function($book) { return (object) $book; }, $books),
            'user' => (object) $user
        ]);
    }

    public function update(Request $request, $id)
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

        User::updateProfile($id, $data);

        return redirect()->route('user.profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
