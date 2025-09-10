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
        $userId = is_array($book) ? ($book['user_id'] ?? null) : $book->user_id;
        $user = $userId ? \App\Models\User::find($userId) : null;
        return view('books.show', compact('book', 'user'));
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


        // Exportação de dados
    public function exportBooks($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $booksCollection = Book::all();
        $books = is_object($booksCollection) && method_exists($booksCollection, 'toArray')
            ? $booksCollection->toArray()
            : (array) $booksCollection;
        return $this->exportData($books, $format, 'livros');
    }

    public function exportUsers($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $users = User::all()->toArray();
        return $this->exportData($users, $format, 'usuarios');
    }

    public function exportCoupons($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $coupons = Coupon::all()->toArray();
        return $this->exportData($coupons, $format, 'cupons');
    }

    private function exportData(array $data, $format, $filename)
    {
        if ($format === 'json') {
            $adapter = new \App\Adapters\JsonExport();
            $content = $adapter->export($data);
            $type = 'application/json';
            $ext = 'json';
        } elseif ($format === 'csv') {
            $adapter = new \App\Adapters\CsvExportAdapter();
            $content = $adapter->export($data);
            $type = 'text/csv';
            $ext = 'csv';
        } else {
            return redirect()->back()->with('error', 'Formato inválido!');
        }
        return response($content)
            ->header('Content-Type', $type)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.' . $ext . '"');
    }
}
