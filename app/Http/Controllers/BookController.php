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
        $inputs = $request->validate([
            'name' => 'required',
            'author' => 'required',
            'genre.*' => 'required',
            'condition' => 'required',
            'price' => 'required',
            'description' => 'required|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $paths = [];
        foreach($request->file('images') as $image){
            $paths[] = $image->store('img.books');
        }

        Book::create([
            'name' => $inputs['name'],
            'author' => $inputs['author'],
            'genre' => json_encode($inputs['genre']),
            'condition' => $inputs['condition'],
            'price' => $inputs['price'],
            'description'=> $inputs['description'],
            'images'=> json_encode($paths),
            'user_id'=> $user->id,
        ]);

        return redirect()->route('user.profile');
    }

    public function edit(){
        return view('books.edit');
    }

    public function update(Request $request, Book $book){
        $book->update($request->all());
        return redirect()->route('user.profile');
    }

    public function show($id){
        $book = Book::findOrFail($id);
        return view('books.show', ['book' => $book]);
    }

    public function destroy(Book $book)
    {
        $images = json_decode($book->images, true);
        if ($images) {
            foreach ($images as $image) {
                \Storage::delete($image);
            }
        }

        $book->delete();

        return redirect()->route('user.profile');
    }
}
