<?php

namespace App\Http\Controllers;

use App\Factories\BookFactoryInterface;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookFactory;

    public function __construct(BookFactoryInterface $bookFactory)
    {
        $this->bookFactory = $bookFactory;
    }

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

        $inputs = $request->validate([
            'name' => 'required',
            'author' => 'required',
            'genre' => 'required|array',
            'condition' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $inputs['genre'] = json_encode($inputs['genre']);
        $inputs['product_type'] = $request->input('product_type', 'fisico');
        $productType = $inputs['product_type'];
        $product = $this->bookFactory->createProduct($productType, $inputs, $request->file('images'), $user->id);
        $this->bookFactory->create($inputs, $request->file('images'), $user->id);

        return redirect()->route('user.profile')->with('success', 'Livro criado com sucesso!');
    }

    public function edit($id)
    {
        $book = Book::find($id);
        return view('books.edit', ['book' => $book]);
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'name' => 'required',
            'author' => 'required',
            'genre' => 'required|array',
            'condition' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $inputs['genre'] = json_encode($inputs['genre']);
        $inputs['product_type'] = $request->input('product_type', 'fisico');

        $book = Book::find($id);

        $this->bookFactory->update($book, $inputs, $request->file('images'));

        return redirect()->route('user.profile')->with('success', 'Livro atualizado com sucesso!');
    }

    public function show($id)
    {
        $book = Book::find($id);
        return view('books.show', ['book' => $book]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        $this->bookFactory->delete($book);

        return redirect()->route('user.profile')->with('success', 'Livro deletado com sucesso!');
    }
}
