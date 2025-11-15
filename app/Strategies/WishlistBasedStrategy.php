<?php

namespace App\Strategies;

use App\Models\User;
use App\Models\Book;

/**
 * Estratégia: Recomendações baseadas na wishlist
 * Recomenda livros similares aos que estão na lista de desejos
 */
class WishlistBasedStrategy implements RecommendationStrategyInterface
{
    public function getRecommendations(int $userId, int $limit = 5): array
    {
        $user = User::find($userId);
        
        if (!$user) {
            return [];
        }

        // Buscar livros na wishlist do usuário
        $wishlistBooks = $user->wishlist()->with('book')->get()->pluck('book');

        if ($wishlistBooks->isEmpty()) {
            return [];
        }

        $wishlistBookIds = $wishlistBooks->pluck('id')->toArray();
        $genres = $wishlistBooks->pluck('genre')->unique()->toArray();
        $authors = $wishlistBooks->pluck('author')->unique()->toArray();

        // Buscar livros similares que não estão na wishlist
        $recommendations = Book::query()
            ->where(function($query) use ($genres, $authors) {
                $query->whereIn('genre', $genres)
                      ->orWhereIn('author', $authors);
            })
            ->whereNotIn('id', $wishlistBookIds)
            ->inRandomOrder()
            ->limit($limit)
            ->get()
            ->toArray();

        return $recommendations;
    }
}
