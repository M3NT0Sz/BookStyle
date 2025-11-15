<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Mostrar formulário para criar avaliação
     */
    public function create($orderId, $bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para avaliar.');
        }

        $user = auth()->user();
        
        // Verificar se pode avaliar
        if (!$user->canReviewBook($bookId, $orderId)) {
            return redirect()->back()->with('error', 'Você não pode avaliar este produto.');
        }

        $order = Order::findOrFail($orderId);
        $book = Book::findOrFail($bookId);

        return view('reviews.create', compact('order', 'book'));
    }

    /**
     * Armazenar nova avaliação
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para avaliar.');
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        $user = auth()->user();

        // Verificar novamente se pode avaliar
        if (!$user->canReviewBook($validated['book_id'], $validated['order_id'])) {
            return redirect()->back()->with('error', 'Você não pode avaliar este produto.');
        }

        // Processar imagens se houver
        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imageUrls[] = Storage::url($path);
            }
        }

        // Criar avaliação
        $review = Review::create([
            'user_id' => $user->id,
            'book_id' => $validated['book_id'],
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'images' => $imageUrls,
            'is_verified_purchase' => true,
            'is_approved' => true
        ]);

        return redirect()->route('books.show', $validated['book_id'])
            ->with('success', 'Avaliação enviada com sucesso!');
    }

    /**
     * Mostrar avaliações de um livro
     */
    public function index($bookId)
    {
        $book = Book::findOrFail($bookId);
        $reviews = Review::where('book_id', $bookId)
            ->approved()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = $book->getAverageRating();
        $totalReviews = $book->getReviewsCount();

        // Distribuição de estrelas
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('book_id', $bookId)
                ->approved()
                ->where('rating', $i)
                ->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? ($count / $totalReviews * 100) : 0
            ];
        }

        return view('reviews.index', compact('book', 'reviews', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }

    /**
     * Excluir avaliação (apenas o autor ou admin)
     */
    public function destroy($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado.');
        }

        $review = Review::findOrFail($id);
        $user = auth()->user();

        // Apenas o autor ou admin pode deletar
        if ($review->user_id !== $user->id && !$user->is_admin) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir esta avaliação.');
        }

        // Deletar imagens se houver
        if ($review->hasImages()) {
            foreach ($review->images as $imageUrl) {
                // Remove /storage/ e converte para path relativo
                $path = str_replace('/storage/', '', $imageUrl);
                $path = preg_replace('#^storage/#', '', $path);
                
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $review->delete();

        return redirect()->back()->with('success', 'Avaliação excluída com sucesso!');
    }

    /**
     * Dar like/útil em uma avaliação (funcionalidade futura)
     */
    public function markAsHelpful($id)
    {
        // Implementação futura para marcar review como útil
        return response()->json(['success' => true]);
    }
}
