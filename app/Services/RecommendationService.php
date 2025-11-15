<?php

namespace App\Services;

use App\Strategies\RecommendationStrategyInterface;
use App\Strategies\PurchaseHistoryStrategy;
use App\Strategies\WishlistBasedStrategy;
use App\Strategies\PopularBooksStrategy;

/**
 * Serviço de Recomendações usando Strategy Pattern
 * Permite alternar entre diferentes estratégias de recomendação
 */
class RecommendationService
{
    private RecommendationStrategyInterface $strategy;

    /**
     * Construtor com estratégia padrão
     */
    public function __construct(?RecommendationStrategyInterface $strategy = null)
    {
        $this->strategy = $strategy ?? new PurchaseHistoryStrategy();
    }

    /**
     * Definir estratégia de recomendação
     */
    public function setStrategy(RecommendationStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Obter recomendações usando a estratégia atual
     */
    public function getRecommendations(int $userId, int $limit = 5): array
    {
        return $this->strategy->getRecommendations($userId, $limit);
    }

    /**
     * Obter recomendações inteligentes (tenta múltiplas estratégias)
     */
    public function getSmartRecommendations(int $userId, int $limit = 5): array
    {
        // Tentar estratégia de histórico de compras primeiro
        $this->setStrategy(new PurchaseHistoryStrategy());
        $recommendations = $this->getRecommendations($userId, $limit);

        // Se não houver resultados, tentar wishlist
        if (empty($recommendations)) {
            $this->setStrategy(new WishlistBasedStrategy());
            $recommendations = $this->getRecommendations($userId, $limit);
        }

        // Se ainda não houver resultados, mostrar populares
        if (empty($recommendations)) {
            $this->setStrategy(new PopularBooksStrategy());
            $recommendations = $this->getRecommendations($userId, $limit);
        }

        return $recommendations;
    }

    /**
     * Obter recomendações mistas (combina estratégias)
     */
    public function getMixedRecommendations(int $userId, int $perStrategy = 2): array
    {
        $recommendations = [];

        // 2 baseados em compras
        $this->setStrategy(new PurchaseHistoryStrategy());
        $recommendations = array_merge($recommendations, $this->getRecommendations($userId, $perStrategy));

        // 2 baseados em wishlist
        $this->setStrategy(new WishlistBasedStrategy());
        $recommendations = array_merge($recommendations, $this->getRecommendations($userId, $perStrategy));

        // Restante dos populares
        $remaining = 6 - count($recommendations);
        if ($remaining > 0) {
            $this->setStrategy(new PopularBooksStrategy());
            $recommendations = array_merge($recommendations, $this->getRecommendations($userId, $remaining));
        }

        // Remover duplicatas
        $uniqueRecommendations = [];
        $seenIds = [];
        
        foreach ($recommendations as $book) {
            if (!in_array($book['id'], $seenIds)) {
                $uniqueRecommendations[] = $book;
                $seenIds[] = $book['id'];
            }
        }

        return array_slice($uniqueRecommendations, 0, 6);
    }
}
