<?php

namespace App\Strategies;

use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

/**
 * Estratégia: Recomendações baseadas em compras anteriores
 * Recomenda livros do mesmo gênero/autor que o usuário já comprou
 */
class PurchaseHistoryStrategy implements RecommendationStrategyInterface
{
    public function getRecommendations(int $userId, int $limit = 5): array
    {
        $user = User::find($userId);
        
        if (!$user) {
            return [];
        }

        // Buscar gêneros e autores dos livros já comprados
        $purchasedBooks = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->where('orders.user_id', $userId)
            ->select('books.genre', 'books.author', 'books.id')
            ->get();

        if ($purchasedBooks->isEmpty()) {
            return [];
        }

        $purchasedBookIds = $purchasedBooks->pluck('id')->toArray();
        $genres = $purchasedBooks->pluck('genre')->unique()->toArray();
        $authors = $purchasedBooks->pluck('author')->unique()->toArray();

        // Buscar livros similares que o usuário ainda não comprou
        $recommendations = Book::query()
            ->where(function($query) use ($genres, $authors) {
                $query->whereIn('genre', $genres)
                      ->orWhereIn('author', $authors);
            })
            ->whereNotIn('id', $purchasedBookIds)
            ->inRandomOrder()
            ->limit($limit)
            ->get()
            ->toArray();

        return $recommendations;
    }
}
