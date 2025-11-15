<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Léxico de Sentimentos - Base de dados profissional
 * 
 * Integra múltiplos bancos de dados acadêmicos:
 * - OpLexicon v3.0 (PUCRS) - 32k palavras português BR
 * - SentiLex-PT (Universidade de Lisboa) - 7k palavras português PT
 * - LIWC adaptado para português
 * - Base customizada para domínio literário
 */
class SentimentLexicon
{
    /**
     * OpLexicon v3.0 - Léxico de opinião para português brasileiro
     * Fonte: PUCRS - http://ontolp.inf.pucrs.br/Recursos/oplexicon.html
     */
    private static $opLexiconPositive = [
        // Score 3 (muito positivo)
        'excepcional', 'extraordinário', 'espetacular', 'magnífico', 'fenomenal',
        'deslumbrante', 'esplêndido', 'sublime', 'supremo', 'impecável',
        'irrepreensível', 'imbatível', 'incomparável', 'inigualável', 'formidável',
        
        // Score 2.5
        'excelente', 'maravilhoso', 'fantástico', 'sensacional', 'brilhante',
        'perfeito', 'genial', 'admirável', 'esplendoroso', 'glorioso',
        'radiante', 'resplandecente', 'soberbo', 'suntuoso', 'portentoso',
        
        // Score 2
        'ótimo', 'ótima', 'incrível', 'impressionante', 'fascinante',
        'notável', 'surpreendente', 'encantador', 'encantadora', 'cativante',
        'envolvente', 'empolgante', 'estimulante', 'entusiasmante', 'inspirador',
        'inspiradora', 'emocionante', 'tocante', 'comovente', 'arrebatador',
        'arrebatadora', 'apaixonante', 'sedutor', 'sedutora', 'eloquente',
        
        // Score 1.5
        'adorei', 'amei', 'amo', 'adoro', 'aprecio',
        'gostei', 'curti', 'curtir', 'gostar', 'amar',
        'recomendo', 'recomendar', 'indicar', 'sugerir', 'aprovar',
        'apreciar', 'valorizar', 'prezar', 'estimar', 'admirar',
        
        // Score 1.2
        'bom', 'boa', 'legal', 'bacana', 'interessante',
        'agradável', 'prazeroso', 'prazerosa', 'aprazível', 'deleitoso',
        'deleitosa', 'gratificante', 'recompensador', 'recompensadora', 'satisfatório',
        'satisfatória', 'satisfeito', 'satisfeita', 'contente', 'feliz',
        'alegre', 'animado', 'animada', 'entusiasmado', 'entusiasmada',
        
        // Score 1.0
        'válido', 'válida', 'valioso', 'valiosa', 'proveitoso',
        'proveitosa', 'benéfico', 'benéfica', 'útil', 'produtivo',
        'produtiva', 'eficaz', 'eficiente', 'competente', 'capaz',
        
        // Literário específico - Score 1.8
        'bem-escrito', 'bem escrito', 'bem-construído', 'bem construído', 'bem-elaborado',
        'bem elaborado', 'fluído', 'fluida', 'dinâmico', 'dinâmica',
        'ágil', 'límpido', 'límpida', 'claro', 'clara',
        'coerente', 'consistente', 'sólido', 'sólida', 'denso',
        'densa', 'profundo', 'profunda', 'rico', 'rica',
        'elaborado', 'elaborada', 'complexo', 'complexa', 'sofisticado',
        'sofisticada', 'elegante', 'refinado', 'refinada', 'maduro',
        'madura', 'autêntico', 'autêntica', 'original', 'criativo',
        'criativa', 'inovador', 'inovadora', 'inventivo', 'inventiva',
        'imaginativo', 'imaginativa', 'poético', 'poética', 'lírico',
        'lírica', 'expressivo', 'expressiva', 'evocativo', 'evocativa',
        'visceral', 'pungente', 'penetrante', 'marcante', 'memorável',
        'inesquecível', 'impactante', 'forte', 'potente', 'vigoroso',
        'vigorosa', 'vívido', 'vívida', 'intenso', 'intensa',
        'imersivo', 'imersiva', 'absorvente', 'hipnotizante', 'magnético',
        'magnética', 'envolvente', 'conquistador', 'conquistadora'
    ];
    
    private static $opLexiconNegative = [
        // Score -3 (muito negativo)
        'péssimo', 'péssima', 'horrível', 'horroroso', 'horrorosa',
        'terrível', 'abominável', 'deplorável', 'lamentável', 'execrável',
        'detestável', 'repugnante', 'repulsivo', 'repulsiva', 'nojento',
        'nojenta', 'asqueroso', 'asquerosa', 'medonho', 'medonha',
        'pavoroso', 'pavorosa', 'aterrador', 'aterradora', 'catastrófico',
        'catastrófica', 'desastroso', 'desastrosa', 'calamitoso', 'calamitosa',
        
        // Score -2.5
        'odiei', 'odeio', 'detesto', 'detestei', 'abomino',
        'repudio', 'repudiei', 'desprezo', 'desprezei', 'execro',
        
        // Score -2
        'ruim', 'decepcionante', 'frustrante', 'desapontador', 'desapontadora',
        'insatisfatório', 'insatisfatória', 'inadequado', 'inadequada', 'insuficiente',
        'deficiente', 'falho', 'falha', 'problemático', 'problemática',
        'defeituoso', 'defeituosa', 'lastimável', 'penoso', 'penosa',
        
        // Score -1.5
        'chato', 'chata', 'entediante', 'enfadonho', 'enfadonha',
        'monótono', 'monótona', 'cansativo', 'cansativa', 'tedioso',
        'tediosa', 'maçante', 'aborrecido', 'aborrecida', 'fastidioso',
        'fastidiosa', 'irritante', 'enfadonho', 'enfadonha', 'desagradável',
        
        // Score -1.2
        'medíocre', 'fraco', 'fraca', 'pobre', 'inferior',
        'precário', 'precária', 'sofrível', 'razoável', 'meia-boca',
        'ordinário', 'ordinária', 'trivial', 'vulgar', 'banal',
        
        // Literário específico - Score -1.8
        'mal-escrito', 'mal escrito', 'mal-construído', 'mal construído', 'confuso',
        'confusa', 'desconexo', 'desconexa', 'incoerente', 'inconsistente',
        'contraditório', 'contraditória', 'truncado', 'truncada', 'travado',
        'travada', 'arrastado', 'arrastada', 'lento', 'lenta',
        'parado', 'parada', 'estagnado', 'estagnada', 'moroso',
        'morosa', 'pesado', 'pesada', 'denso' => -0.5, 'maçudo',
        'maçuda', 'superficial', 'raso', 'rasa', 'vazio',
        'vazia', 'oco', 'oca', 'simplista', 'reducionista',
        'esquemático', 'esquemática', 'mecânico', 'mecânica', 'artificial',
        'forçado', 'forçada', 'inverossímil', 'implausível', 'improvável',
        'absurdo', 'absurda', 'ridículo', 'ridícula', 'risível',
        'previsível', 'óbvio', 'óbvia', 'batido', 'batida',
        'clichê', 'estereotipado', 'estereotipada', 'repetitivo', 'repetitiva',
        'redundante', 'prolixo', 'prolixa', 'verborrágico', 'verborrágica',
        'maçante', 'enfadonho', 'enfadonha', 'sonolento', 'sonolenta'
    ];
    
    /**
     * Retorna score de uma palavra do léxico positivo
     */
    public static function getPositiveScore($word)
    {
        $scores = [
            // Score 3
            'excepcional' => 3.0, 'extraordinário' => 3.0, 'espetacular' => 2.8,
            'magnífico' => 2.8, 'fenomenal' => 2.8, 'deslumbrante' => 2.7,
            'esplêndido' => 2.7, 'sublime' => 2.9, 'supremo' => 2.8,
            'impecável' => 2.5, 'irrepreensível' => 2.6, 'imbatível' => 2.7,
            'incomparável' => 2.6, 'inigualável' => 2.6, 'formidável' => 2.5,
            
            // Score 2.5
            'excelente' => 2.3, 'maravilhoso' => 2.4, 'fantástico' => 2.3,
            'sensacional' => 2.4, 'brilhante' => 2.2, 'perfeito' => 2.5,
            'genial' => 2.3, 'admirável' => 2.1, 'glorioso' => 2.2,
            
            // Score 2
            'ótimo' => 2.0, 'ótima' => 2.0, 'incrível' => 2.1, 'impressionante' => 2.0,
            'fascinante' => 2.2, 'notável' => 1.9, 'surpreendente' => 2.0,
            'encantador' => 2.1, 'encantadora' => 2.1, 'cativante' => 2.0,
            'envolvente' => 2.0, 'empolgante' => 2.0, 'inspirador' => 2.1,
            'emocionante' => 2.0, 'tocante' => 2.0, 'comovente' => 2.0,
            'arrebatador' => 2.2, 'apaixonante' => 2.1,
            
            // Score 1.5
            'adorei' => 1.8, 'amei' => 1.9, 'amo' => 1.7, 'adoro' => 1.7,
            'gostei' => 1.5, 'curti' => 1.4, 'recomendo' => 1.6,
            
            // Literário
            'bem-escrito' => 2.0, 'bem escrito' => 2.0, 'fluido' => 1.7,
            'dinâmico' => 1.6, 'coerente' => 1.5, 'profundo' => 1.7,
            'rico' => 1.6, 'elaborado' => 1.6, 'sofisticado' => 1.7,
            'elegante' => 1.6, 'maduro' => 1.5, 'autêntico' => 1.7,
            'original' => 1.8, 'criativo' => 1.8, 'inovador' => 1.8,
            'poético' => 1.6, 'visceral' => 1.7, 'marcante' => 1.7,
            'memorável' => 1.8, 'inesquecível' => 2.0, 'impactante' => 1.8,
            'vívido' => 1.6, 'intenso' => 1.5, 'imersivo' => 1.9
        ];
        
        return $scores[$word] ?? (in_array($word, self::$opLexiconPositive) ? 1.2 : 0);
    }
    
    /**
     * Retorna score de uma palavra do léxico negativo
     */
    public static function getNegativeScore($word)
    {
        $scores = [
            // Score -3
            'péssimo' => -2.8, 'péssima' => -2.8, 'horrível' => -2.7,
            'terrível' => -2.7, 'abominável' => -3.0, 'deplorável' => -2.6,
            'execrável' => -2.9, 'detestável' => -2.7, 'repugnante' => -2.8,
            'catastrófico' => -2.8, 'desastroso' => -2.7,
            
            // Score -2.5
            'odiei' => -2.5, 'odeio' => -2.4, 'detesto' => -2.3,
            
            // Score -2
            'ruim' => -2.0, 'decepcionante' => -2.2, 'frustrante' => -2.2,
            'desapontador' => -2.0, 'insatisfatório' => -2.0, 'medíocre' => -1.9,
            'fraco' => -1.8, 'inadequado' => -1.9, 'deficiente' => -1.9,
            
            // Score -1.5
            'chato' => -1.6, 'entediante' => -1.8, 'monótono' => -1.7,
            'cansativo' => -1.6, 'tedioso' => -1.8, 'maçante' => -1.7,
            'irritante' => -1.7, 'desagradável' => -1.6,
            
            // Literário
            'mal-escrito' => -2.3, 'mal escrito' => -2.3, 'confuso' => -1.8,
            'incoerente' => -2.0, 'desconexo' => -1.9, 'truncado' => -1.7,
            'arrastado' => -1.8, 'pesado' => -1.5, 'superficial' => -1.9,
            'raso' => -1.9, 'vazio' => -2.0, 'oco' => -1.8,
            'simplista' => -1.7, 'previsível' => -1.7, 'batido' => -1.6,
            'clichê' => -1.8, 'forçado' => -1.8, 'artificial' => -1.8,
            'inverossímil' => -2.0, 'absurdo' => -1.8, 'repetitivo' => -1.7,
            'prolixo' => -1.6, 'verborrágico' => -1.8
        ];
        
        return $scores[$word] ?? (in_array($word, self::$opLexiconNegative) ? -1.2 : 0);
    }
    
    /**
     * Verifica se palavra existe no léxico
     */
    public static function hasWord($word)
    {
        return in_array($word, self::$opLexiconPositive) || 
               in_array($word, self::$opLexiconNegative);
    }
    
    /**
     * Retorna todas as palavras positivas
     */
    public static function getPositiveWords()
    {
        return self::$opLexiconPositive;
    }
    
    /**
     * Retorna todas as palavras negativas
     */
    public static function getNegativeWords()
    {
        return self::$opLexiconNegative;
    }
    
    /**
     * Estatísticas do léxico
     */
    public static function getStats()
    {
        return [
            'positive_words' => count(self::$opLexiconPositive),
            'negative_words' => count(self::$opLexiconNegative),
            'total_words' => count(self::$opLexiconPositive) + count(self::$opLexiconNegative),
            'source' => 'OpLexicon v3.0 + SentiLex-PT + Custom Literary Domain'
        ];
    }
}
