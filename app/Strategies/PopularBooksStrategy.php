<?php

namespace App\Strategies;

use App\Models\Book;

/**
 * Estratégia: Recomendações de livros populares
 * Recomenda os livros mais bem avaliados e mais vendidos
 */
class PopularBooksStrategy implements RecommendationStrategyInterface
{
    public function getRecommendations(int $userId, int $limit = 5): array
    {
        // Buscar livros mais populares (com mais avaliações positivas)
        $recommendations = Book::query()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->limit($limit)
            ->get()
            ->toArray();

        return $recommendations;
    }
}
