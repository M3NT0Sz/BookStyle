<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        return view('admin.dashboard');
    }

    public function books()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $books = Book::all();
        return view('admin.books', compact('books'));
    }

    public function users()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $users = User::all();
        return view('admin.users', compact('users'));
    }


    public function coupons()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $coupons = Coupon::all();
        return view('admin.coupons', compact('coupons'));
    }


    public function showBook($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $book = Book::find($id);
        if (!$book) {
            return redirect()->route('admin.books')->with('error', 'Livro não encontrado.');
        }
        $user = \App\Models\User::find($book->user_id);
        return view('admin.book_show', compact('book', 'user'));
    }



    public function destroyBook($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            return redirect()->route('admin.books')->with('success', 'Livro deletado com sucesso!');
        }
        return redirect()->route('admin.books')->with('error', 'Livro não encontrado.');
    }

    public function editUser($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Usuário não encontrado.');
        }
        return view('admin.user_edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $user = User::query()->where('id', $id)->first();
        if (!$user || !is_object($user)) {
            return redirect()->route('admin.users')->with('error', 'Usuário não encontrado.');
        }
        $data = $request->validate([
            'is_admin' => 'required|boolean',
        ]);
        $user->is_admin = $data['is_admin'];
        $user->save();
        return redirect()->route('admin.users')->with('success', 'Status de administrador atualizado!');
    }
}
