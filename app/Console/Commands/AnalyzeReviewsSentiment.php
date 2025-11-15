<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Review;
use App\Services\SentimentAnalysisService;

class AnalyzeReviewsSentiment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:analyze-sentiment {--force : Força reanálise de todas as reviews}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analisa o sentimento de todas as avaliações usando IA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando análise de sentimentos...');
        
        $query = Review::query();
        
        // Se não forçar, apenas reviews sem análise
        if (!$this->option('force')) {
            $query->whereNull('sentiment');
        }
        
        $reviews = $query->get();
        $total = $reviews->count();
        
        if ($total === 0) {
            $this->info('Nenhuma review para analisar!');
            return 0;
        }
        
        $this->info("Analisando {$total} avaliações...");
        $bar = $this->output->createProgressBar($total);
        
        $stats = ['POSITIVO' => 0, 'NEGATIVO' => 0, 'NEUTRO' => 0];
        
        foreach ($reviews as $review) {
            if (!empty($review->comment)) {
                $analysis = SentimentAnalysisService::analyze($review->comment);
                
                $review->update([
                    'sentiment' => $analysis['sentiment'],
                    'sentiment_score' => $analysis['score'],
                    'sentiment_confidence' => $analysis['confidence']
                ]);
                
                $stats[$analysis['sentiment']]++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info('✓ Análise concluída!');
        $this->newLine();
        $this->table(
            ['Sentimento', 'Quantidade', 'Percentual'],
            [
                ['😊 Positivo', $stats['POSITIVO'], round($stats['POSITIVO']/$total*100, 1) . '%'],
                ['😐 Neutro', $stats['NEUTRO'], round($stats['NEUTRO']/$total*100, 1) . '%'],
                ['😞 Negativo', $stats['NEGATIVO'], round($stats['NEGATIVO']/$total*100, 1) . '%'],
            ]
        );
        
        return 0;
    }
}
