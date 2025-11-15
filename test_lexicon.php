<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTE DE GERAÇÃO DE TEXTO MELHORADA ===\n\n";

// Criar mock de reviews para teste
$mockReviews = collect([
    (object)[
        'rating' => 5,
        'sentiment' => 'POSITIVO',
        'comment' => 'Livro excepcional! A narrativa é envolvente e os personagens são profundos. Recomendo muito!'
    ],
    (object)[
        'rating' => 4,
        'sentiment' => 'POSITIVO',
        'comment' => 'Gostei bastante. A escrita é fluida e a história prende do início ao fim.'
    ],
    (object)[
        'rating' => 1,
        'sentiment' => 'NEGATIVO',
        'comment' => 'Péssimo. Enredo confuso e personagens rasos. Muito decepcionante.'
    ]
]);

echo "📚 Reviews de teste: " . $mockReviews->count() . "\n";
echo "⭐ Média: " . round($mockReviews->avg('rating'), 1) . " estrelas\n\n";

echo "=== ANÁLISE GERADA PELA IA ===\n\n";

$analise = \App\Services\SentimentAnalysisService::generateSummary($mockReviews);
echo $analise . "\n\n";

echo "=== TESTE COM AVALIAÇÕES REAIS ===\n\n";

$reviewsReais = \App\Models\Review::where('book_id', 2)->get();
if ($reviewsReais->count() > 0) {
    echo "📚 Reviews reais encontradas: " . $reviewsReais->count() . "\n";
    echo "⭐ Média: " . round($reviewsReais->avg('rating'), 1) . " estrelas\n\n";
    
    $analiseReal = \App\Services\SentimentAnalysisService::generateSummary($reviewsReais);
    echo $analiseReal . "\n";
} else {
    echo "Nenhuma review real encontrada para o livro ID 2\n";
}
