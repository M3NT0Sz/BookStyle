<?php

namespace App\Strategies;

/**
 * Interface para estratégias de recomendação
 * Pattern: Strategy
 */
interface RecommendationStrategyInterface
{
    /**
     * Obter livros recomendados para um usuário
     * 
     * @param int $userId ID do usuário
     * @param int $limit Quantidade de recomendações
     * @return array Lista de livros recomendados
     */
    public function getRecommendations(int $userId, int $limit = 5): array;
}
