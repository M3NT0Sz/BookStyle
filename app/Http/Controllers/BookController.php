<?php

namespace App\Http\Controllers;

use App\Factories\BookFactory;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookFactory;

    public function __construct()
    {
        $this->bookFactory = new BookFactory();
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
            'product_type' => 'required',
            // Campos específicos do produto (não required para não travar outros tipos)
            'file_format' => 'nullable',
            'file_size' => 'nullable',
            'has_drm' => 'nullable',
            'books_count' => 'nullable',
            'titles' => 'nullable',
            'extras' => 'nullable',
            'issue_number' => 'nullable',
            'illustrator' => 'nullable',
            'is_colored' => 'nullable',
            'pages' => 'nullable',
            'cover_type' => 'nullable',
            'weight' => 'nullable',
            'dimensions' => 'nullable',
        ]);

        $inputs['genre'] = json_encode($inputs['genre']);
        $inputs['product_type'] = $request->input('product_type', 'fisico');
        $inputs['user_id'] = $user->id;

        $images = $request->file('images', []);
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $image->store('img.books');
        }
        $inputs['images'] = json_encode($paths);

        // Garante que campos array sejam salvos como json
        if (isset($inputs['titles']) && is_array($inputs['titles'])) {
            $inputs['titles'] = json_encode($inputs['titles']);
        }
        if (isset($inputs['extras']) && is_array($inputs['extras'])) {
            $inputs['extras'] = json_encode($inputs['extras']);
        }
        if (isset($inputs['dimensions']) && is_array($inputs['dimensions'])) {
            $inputs['dimensions'] = json_encode($inputs['dimensions']);
        }

        Book::create($inputs);

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
            'product_type' => 'required',
            // Campos específicos do produto
            'file_format' => 'nullable',
            'file_size' => 'nullable',
            'has_drm' => 'nullable',
            'books_count' => 'nullable',
            'titles' => 'nullable',
            'extras' => 'nullable',
            'issue_number' => 'nullable',
            'illustrator' => 'nullable',
            'is_colored' => 'nullable',
            'pages' => 'nullable',
            'cover_type' => 'nullable',
            'weight' => 'nullable',
            'dimensions' => 'nullable',
        ]);

        $inputs['genre'] = json_encode($inputs['genre']);
        $inputs['product_type'] = $request->input('product_type', 'fisico');

        $book = Book::find($id);
        $inputs['user_id'] = $book['user_id'] ?? null;

        // Atualização das imagens
        $images = $request->file('images', []);
        if ($images && count($images) > 0) {
            $paths = [];
            foreach ($images as $image) {
                $paths[] = $image->store('img.books');
            }
            $inputs['images'] = json_encode($paths);
        } else {
            // Mantém as imagens antigas se não enviar novas
            $inputs['images'] = $book['images'] ?? null;
        }

        // Garante que campos array sejam salvos como json
        if (isset($inputs['titles']) && is_array($inputs['titles'])) {
            $inputs['titles'] = json_encode($inputs['titles']);
        }
        if (isset($inputs['extras']) && is_array($inputs['extras'])) {
            $inputs['extras'] = json_encode($inputs['extras']);
        }
        if (isset($inputs['dimensions']) && is_array($inputs['dimensions'])) {
            $inputs['dimensions'] = json_encode($inputs['dimensions']);
        }

        // Corrige campos numéricos para não irem como null se vierem string vazia
        foreach(['file_size','books_count','issue_number','pages','weight'] as $campoNum) {
            if (array_key_exists($campoNum, $inputs) && $inputs[$campoNum] === '') {
                $inputs[$campoNum] = 0;
            }
        }

        Book::update($id, $inputs);

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
        Book::delete($book['id']);
        return redirect()->route('user.profile')->with('success', 'Livro deletado com sucesso!');
    }
}
