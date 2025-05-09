<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', ['books' => $books]);
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validação dos dados
        $inputs = $request->validate([
            'name' => 'required',
            'author' => 'required',
            'genre' => 'required|array', // Certifique-se de validar como array
            'condition' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Converte o array de gênero para JSON
        $inputs['genre'] = json_encode($inputs['genre']);

        // Criação do livro
        Book::createBook($inputs, $request->file('images'), $user->id);

        return redirect()->route('user.profile')->with('success', 'Livro criado com sucesso!');
    }

    public function edit(Book $book)
    {
        return view('books.edit', ['book' => $book]);
    }

    public function update(Request $request, Book $book)
    {
        $inputs = $request->validate([
            'name' => 'required',
            'author' => 'required',
            'genre.*' => 'required',
            'condition' => 'required',
            'price' => 'required',
            'description' => 'required|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book->updateBook($inputs, $request->file('images'));

        return redirect()->route('user.profile');
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', ['book' => $book]);
    }

    public function destroy(Book $book)
    {
        $book->deleteBook();

        return redirect()->route('user.profile');
    }
}
