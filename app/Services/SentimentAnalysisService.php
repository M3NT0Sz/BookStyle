<?php

namespace App\Services;

use App\Services\SentimentLexicon;

class SentimentAnalysisService
{
    /**
     * IA DE ANÁLISE DE SENTIMENTO - VERSÃO COM LÉXICO PROFISSIONAL
     * 
     * Integra bancos de dados acadêmicos:
     * - OpLexicon v3.0 (PUCRS) - 32k palavras português BR
     * - SentiLex-PT (Universidade de Lisboa)
     * - Base customizada para domínio literário (300+ termos específicos)
     * 
     * Análise contextual avançada com:
     * - Pesos específicos por palavra (0.5 a 3.0)
     * - Análise de frases compostas e expressões idiomáticas
     * - Detecção de intensificadores, redutores e negadores
     * - Análise de emojis e pontuação
     * - Contexto semântico (até 2 palavras anteriores)
     */
    public static function analyze($text)
    {
        if (empty($text)) {
            return ['sentiment' => 'NEUTRO', 'score' => 0, 'confidence' => 0];
        }

        $text = mb_strtolower($text, 'UTF-8');
        
        // ===== USAR LÉXICO PROFISSIONAL =====
        // Carrega palavras do banco de dados acadêmico
        $lexiconPositive = SentimentLexicon::getPositiveWords();
        $lexiconNegative = SentimentLexicon::getNegativeWords();
        
        // Palavras POSITIVAS adicionais (complemento)
        $positiveWords = [
            // Extremamente positivo (2.0-2.5)
            'excepcional' => 2.5, 'extraordinário' => 2.5, 'magnífico' => 2.3,
            'espetacular' => 2.3, 'sensacional' => 2.2, 'fenomenal' => 2.2,
            'impecável' => 2.0, 'perfeito' => 2.0, 'brilhante' => 2.0,
            'genial' => 2.0, 'obra-prima' => 2.5, 'obra prima' => 2.5,
            
            // Muito positivo (1.5-1.9)
            'excelente' => 1.8, 'maravilhoso' => 1.8, 'fantástico' => 1.8,
            'incrível' => 1.7, 'fascinante' => 1.7, 'impressionante' => 1.7,
            'ótimo' => 1.6, 'notável' => 1.6, 'admirável' => 1.6,
            'surpreendente' => 1.6, 'encantador' => 1.7, 'cativante' => 1.7,
            'envolvente' => 1.6, 'empolgante' => 1.6, 'inspirador' => 1.7,
            'emocionante' => 1.6, 'tocante' => 1.6, 'comovente' => 1.6,
            'marcante' => 1.5, 'memorável' => 1.5,
            
            // Positivo (1.0-1.4)
            'adorei' => 1.4, 'amei' => 1.4, 'amo' => 1.3,
            'gostei' => 1.2, 'curti' => 1.2, 'aprovei' => 1.2,
            'recomendo' => 1.3, 'bom' => 1.0, 'boa' => 1.0,
            'legal' => 1.0, 'bacana' => 1.0, 'interessante' => 1.1,
            'agradável' => 1.1, 'satisfeito' => 1.2, 'feliz' => 1.2,
            'contente' => 1.1, 'prazeroso' => 1.2, 'divertido' => 1.2,
            'valeu' => 1.1, 'vale' => 1.0, 'compensa' => 1.2,
            'gratificante' => 1.3, 'enriquecedor' => 1.4,
            
            // Qualidades específicas de livros
            'profundo' => 1.3, 'rico' => 1.2, 'detalhado' => 1.2,
            'complexo' => 1.1, 'bem-escrito' => 1.5, 'bem escrito' => 1.5,
            'fluido' => 1.3, 'dinâmico' => 1.2, 'original' => 1.4,
            'criativo' => 1.4, 'inovador' => 1.4, 'reflexivo' => 1.3,
            'inteligente' => 1.3, 'coerente' => 1.2, 'consistente' => 1.2,
            'maduro' => 1.2, 'autêntico' => 1.3, 'realista' => 1.1,
            'verossímil' => 1.2, 'crível' => 1.1, 'elaborado' => 1.3,
            'sofisticado' => 1.3, 'elegante' => 1.2, 'poético' => 1.3,
            'lírico' => 1.2, 'visceral' => 1.3, 'intenso' => 1.2,
            'vívido' => 1.3, 'imersivo' => 1.4, 'denso' => 1.1
        ];
        
        // Palavras NEGATIVAS com pesos específicos
        $negativeWords = [
            // Extremamente negativo (2.0-2.5)
            'péssimo' => 2.3, 'horrível' => 2.2, 'terrível' => 2.2,
            'abominável' => 2.5, 'deplorável' => 2.3, 'lamentável' => 2.0,
            'desastroso' => 2.2, 'catastrófico' => 2.3, 'horroroso' => 2.2,
            'medonho' => 2.0, 'detestável' => 2.1, 'execrável' => 2.4,
            'repugnante' => 2.3, 'insuportável' => 2.2,
            
            // Muito negativo (1.5-1.9)
            'ruim' => 1.6, 'decepcionante' => 1.8, 'frustrante' => 1.8,
            'desapontador' => 1.7, 'insatisfatório' => 1.7, 'medíocre' => 1.6,
            'fraco' => 1.5, 'pobre' => 1.5, 'inferior' => 1.6,
            'deficiente' => 1.6, 'inadequado' => 1.6, 'insuficiente' => 1.5,
            'falho' => 1.5, 'problemático' => 1.5, 'defeituoso' => 1.6,
            
            // Negativo (1.0-1.4)
            'chato' => 1.3, 'entediante' => 1.4, 'monótono' => 1.4,
            'cansativo' => 1.3, 'tedioso' => 1.4, 'maçante' => 1.4,
            'sonolento' => 1.3, 'arrastado' => 1.3, 'lento' => 1.2,
            'parado' => 1.2, 'desinteressante' => 1.4, 'sem-graça' => 1.3,
            'sem graça' => 1.3, 'apático' => 1.2, 'morno' => 1.2,
            'frio' => 1.1, 'distante' => 1.1,
            
            // Críticas específicas de livros
            'confuso' => 1.4, 'complicado' => 1.2, 'incompreensível' => 1.7,
            'incoerente' => 1.6, 'desconexo' => 1.5, 'desconexa' => 1.5,
            'superficial' => 1.5, 'raso' => 1.5, 'vazio' => 1.6,
            'oco' => 1.5, 'simplista' => 1.4, 'previsível' => 1.4,
            'repetitivo' => 1.5, 'redundante' => 1.4, 'batido' => 1.3,
            'clichê' => 1.4, 'forçado' => 1.5, 'artificial' => 1.5,
            'inverossímil' => 1.6, 'implausível' => 1.6, 'absurdo' => 1.5,
            'mal-escrito' => 1.8, 'mal escrito' => 1.8, 'truncado' => 1.5,
            'travado' => 1.4, 'difícil' => 1.1, 'pesado' => 1.2,
            'prolixo' => 1.3, 'verborrágico' => 1.4, 'arrastado' => 1.4
        ];
        
        // Intensificadores (multiplicam o score)
        $intensifiers = [
            'muito' => 1.5, 'super' => 1.8, 'mega' => 1.8, 'ultra' => 2.0,
            'extremamente' => 2.0, 'incrivelmente' => 1.8, 'absurdamente' => 2.0,
            'demais' => 1.6, 'bastante' => 1.4, 'bem' => 1.3, 'tão' => 1.4,
            'totalmente' => 1.7, 'completamente' => 1.7, 'absolutamente' => 1.8,
            'profundamente' => 1.6, 'imensamente' => 1.8, 'verdadeiramente' => 1.5,
            'realmente' => 1.3, 'certamente' => 1.2
        ];
        
        // Redutores de intensidade
        $reducers = [
            'meio' => 0.6, 'um pouco' => 0.5, 'levemente' => 0.5, 'pouco' => 0.6,
            'razoavelmente' => 0.7, 'relativamente' => 0.7, 'mais ou menos' => 0.5,
            'quase' => 0.8, 'praticamente' => 0.9, 'até' => 0.8
        ];
        
        // Negadores (invertem polaridade)
        $negators = ['não', 'nunca', 'jamais', 'nada', 'nem', 'nenhum', 'nenhuma', 'sem', 'falta', 'longe'];
        
        $score = 0;
        $totalWords = 0;
        
        // ===== ANÁLISE CONTEXTUAL =====
        $words = preg_split('/\s+/', $text);
        $wordsCount = count($words);
        
        for ($i = 0; $i < $wordsCount; $i++) {
            $word = trim($words[$i], '.,!?;:()[]"\'');
            
            $multiplier = 1.0;
            $isNegated = false;
            
            // Analisar até 2 palavras anteriores (contexto)
            if ($i > 0) {
                $prevWord1 = trim($words[$i-1], '.,!?;:()[]"\'');
                
                // Intensificadores
                if (isset($intensifiers[$prevWord1])) {
                    $multiplier *= $intensifiers[$prevWord1];
                }
                // Redutores
                if (isset($reducers[$prevWord1])) {
                    $multiplier *= $reducers[$prevWord1];
                }
                // Negadores
                if (in_array($prevWord1, $negators)) {
                    $isNegated = true;
                }
                
                // Verificar 2 palavras atrás
                if ($i > 1) {
                    $prevWord2 = trim($words[$i-2], '.,!?;:()[]"\'');
                    if (in_array($prevWord2, $negators)) {
                        $isNegated = true;
                    }
                    if (isset($intensifiers[$prevWord2])) {
                        $multiplier *= ($intensifiers[$prevWord2] * 0.7); // Menor impacto
                    }
                }
            }
            
            // Verificar no léxico profissional primeiro
            $lexiconScore = SentimentLexicon::getPositiveScore($word);
            if ($lexiconScore > 0) {
                $score += ($isNegated ? -1 : 1) * $lexiconScore * $multiplier;
                $totalWords++;
            } else {
                $lexiconScore = SentimentLexicon::getNegativeScore($word);
                if ($lexiconScore < 0) {
                    $score += ($isNegated ? 1 : -1) * abs($lexiconScore) * $multiplier;
                    $totalWords++;
                }
                // Fallback para dicionário manual
                elseif (isset($positiveWords[$word])) {
                    $wordWeight = $positiveWords[$word];
                    $score += ($isNegated ? -1 : 1) * $wordWeight * $multiplier;
                    $totalWords++;
                }
                elseif (isset($negativeWords[$word])) {
                    $wordWeight = $negativeWords[$word];
                    $score += ($isNegated ? 1 : -1) * $wordWeight * $multiplier;
                    $totalWords++;
                }
            }
        }
        
        // ===== FRASES COMPOSTAS E EXPRESSÕES =====
        $negativePhrases = [
            'não recomendo' => -2.5,
            'não vale a pena' => -2.3,
            'não vale' => -1.8,
            'não gostei' => -1.8,
            'não curti' => -1.7,
            'perda de tempo' => -2.5,
            'desperdício de tempo' => -2.5,
            'dinheiro jogado fora' => -3.0,
            'me arrependi' => -2.3,
            'esperava mais' => -1.5,
            'deixou a desejar' => -1.8,
            'não compre' => -2.5,
            'não vale nada' => -2.5,
            'pior livro' => -2.8,
            'não consegui terminar' => -2.0,
            'desisti de ler' => -2.2,
            'perdi meu tempo' => -2.3,
            'não recomendo nem' => -3.0
        ];
        
        $positivePhrases = [
            'super recomendo' => 2.5,
            'recomendo muito' => 2.3,
            'vale muito a pena' => 2.5,
            'vale a pena' => 2.0,
            'adorei demais' => 2.5,
            'amei demais' => 2.5,
            'melhor livro' => 3.0,
            'um dos melhores' => 2.5,
            'obra prima' => 3.0,
            'obra-prima' => 3.0,
            'não consegui largar' => 2.5,
            'li de uma vez' => 2.3,
            'li num fôlego' => 2.5,
            'quero ler de novo' => 2.3,
            'vou reler' => 2.0,
            'simplesmente incrível' => 2.8,
            'indispensável' => 2.5,
            'leitura obrigatória' => 2.5,
            'compre sem medo' => 2.3
        ];
        
        foreach ($negativePhrases as $phrase => $value) {
            if (strpos($text, $phrase) !== false) {
                $score += $value;
                $totalWords += 3; // Peso maior para frases
            }
        }
        
        foreach ($positivePhrases as $phrase => $value) {
            if (strpos($text, $phrase) !== false) {
                $score += $value;
                $totalWords += 3;
            }
        }
        
        // ===== ANÁLISE DE EMOJIS =====
        $emojis = [
            '😊' => 1.3, '😃' => 1.3, '😄' => 1.3, '😁' => 1.3, '🥰' => 2.0, '😍' => 2.0,
            '👍' => 1.6, '👏' => 1.6, '❤️' => 2.2, '💖' => 2.0, '💕' => 1.8, '💗' => 1.8,
            '⭐' => 1.2, '🌟' => 1.4, '✨' => 1.1, '🔥' => 1.7, '💯' => 2.0, '😎' => 1.4,
            '🤩' => 2.0, '😌' => 1.1, '🙂' => 1.0, '😀' => 1.2, '🎉' => 1.5, '🎊' => 1.5,
            '😢' => -1.6, '😭' => -2.0, '😞' => -1.6, '😔' => -1.5, '😟' => -1.4,
            '😠' => -2.2, '😡' => -2.5, '🤬' => -3.0, '👎' => -2.0, '💔' => -2.3,
            '😴' => -1.4, '🥱' => -1.5, '😒' => -1.5, '🙄' => -1.6, '😤' => -1.7,
            '🤮' => -2.8, '🤢' => -2.3, '😵' => -1.8, '😖' => -1.7, '😣' => -1.6
        ];
        
        foreach ($emojis as $emoji => $value) {
            $count = mb_substr_count($text, $emoji);
            if ($count > 0) {
                $score += $value * $count;
                $totalWords += $count * 0.8;
            }
        }
        
        // ===== ANÁLISE DE PONTUAÇÃO =====
        $exclamationCount = substr_count($text, '!');
        $questionCount = substr_count($text, '?');
        
        // Múltiplas exclamações aumentam emoção
        if ($exclamationCount >= 3) {
            if ($score > 0) {
                $score *= 1.25; // Aumenta entusiasmo positivo
            } else if ($score < 0) {
                $score *= 1.2; // Aumenta frustração negativa
            }
        }
        
        // Reticências podem indicar decepção
        if (substr_count($text, '...') >= 2 && $score < 0) {
            $score *= 1.15;
        }
        
        // ===== CÁLCULO FINAL =====
        $finalScore = $totalWords > 0 ? $score / max(1, $totalWords) : 0;
        
        // Calcular confiança (0-100)
        $wordCoverage = min(1, $totalWords / max(1, $wordsCount * 0.25));
        $scoreStrength = min(1, abs($finalScore) / 1.5);
        $textLength = min(1, mb_strlen($text) / 50);
        $confidence = ($wordCoverage * 0.5 + $scoreStrength * 0.35 + $textLength * 0.15) * 100;
        
        // Determinar sentimento com limiares otimizados
        if ($finalScore > 0.5) {
            $sentiment = 'POSITIVO';
        } elseif ($finalScore < -0.5) {
            $sentiment = 'NEGATIVO';
        } elseif ($finalScore > 0.15) {
            $sentiment = 'POSITIVO';
        } elseif ($finalScore < -0.15) {
            $sentiment = 'NEGATIVO';
        } else {
            $sentiment = 'NEUTRO';
        }
        
        return [
            'sentiment' => $sentiment,
            'score' => round($finalScore, 2),
            'confidence' => round($confidence, 2)
        ];
    }

    /**
     * IA DE GERAÇÃO DE TEXTO - VERSÃO AVANÇADA COM NLG
     * 
     * Gera análises descritivas usando Natural Language Generation:
     * - Variações linguísticas contextuais (evita repetição)
     * - Adaptação de tom baseada em dados quantitativos
     * - Extração inteligente de insights dos comentários
     * - Estrutura narrativa coerente e fluida
     * - Recomendações personalizadas por perfil de leitor
     */
    public static function generateSummary($reviews)
    {
        // Converter array para collection se necessário
        if (is_array($reviews)) {
            $reviews = collect($reviews);
        }
        
        if (!$reviews || $reviews->isEmpty()) {
            return 'Ainda não há avaliações para este livro.';
        }

        // ===== ANÁLISE QUANTITATIVA =====
        $totalReviews = $reviews->count();
        $totalRating = 0;
        $sentiments = ['POSITIVO' => 0, 'NEGATIVO' => 0, 'NEUTRO' => 0];
        $ratingsCount = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $palavrasRelevantes = [];
        $aspectosMencionados = [
            'personagens' => 0, 'enredo' => 0, 'narrativa' => 0, 'escrita' => 0,
            'final' => 0, 'ritmo' => 0, 'história' => 0, 'autor' => 0,
            'emoção' => 0, 'reflexão' => 0, 'profundidade' => 0
        ];
        
        // Stopwords expandidas e contextualizadas
        $stopWords = ['o', 'a', 'de', 'do', 'da', 'em', 'um', 'uma', 'os', 'as', 'dos', 'das',
                     'para', 'com', 'por', 'que', 'e', 'é', 'são', 'mais', 'foi', 'muito', 'super',
                     'bem', 'mal', 'esse', 'essa', 'este', 'esta', 'livro', 'produto', 'coisa',
                     'sei', 'acho', 'achei', 'ficou', 'fica', 'está', 'estava', 'ser', 'isso',
                     'ter', 'fazer', 'dizer', 'porque', 'quando', 'onde', 'como', 'qual', 'sobre',
                     'seu', 'sua', 'dele', 'dela', 'nos', 'nas', 'pelo', 'pela', 'até', 'após'];
        
        // ===== PROCESSAR REVIEWS =====
        foreach ($reviews as $review) {
            $rating = $review->rating ?? 0;
            $totalRating += $rating;
            $ratingsCount[$rating]++;
            
            $sentiment = $review->sentiment ?? 'NEUTRO';
            $sentiments[$sentiment]++;
            
            // Análise textual profunda
            if (!empty($review->comment)) {
                $texto = mb_strtolower($review->comment, 'UTF-8');
                
                // Detectar aspectos mencionados
                foreach ($aspectosMencionados as $aspecto => $count) {
                    if (strpos($texto, $aspecto) !== false) {
                        $aspectosMencionados[$aspecto]++;
                    }
                }
                
                // Extrair palavras-chave com peso por frequência
                $palavras = preg_split('/[\s,.:;!?()]+/', $texto);
                foreach ($palavras as $palavra) {
                    $palavra = trim($palavra, '"\'');
                    if (strlen($palavra) > 4 && !in_array($palavra, $stopWords) && !is_numeric($palavra)) {
                        if (!isset($palavrasRelevantes[$palavra])) {
                            $palavrasRelevantes[$palavra] = 0;
                        }
                        $palavrasRelevantes[$palavra]++;
                    }
                }
            }
        }
        
        // ===== MÉTRICAS CALCULADAS =====
        $avgRating = round($totalRating / $totalReviews, 1);
        $percPositivos = round(($sentiments['POSITIVO'] / $totalReviews) * 100);
        $percNegativos = round(($sentiments['NEGATIVO'] / $totalReviews) * 100);
        $percNeutros = round(($sentiments['NEUTRO'] / $totalReviews) * 100);
        
        // Distribuição de notas (polarização)
        $notasAltas = $ratingsCount[5] + $ratingsCount[4];
        $notasBaixas = $ratingsCount[1] + $ratingsCount[2];
        $polarizacao = abs($notasAltas - $notasBaixas) / $totalReviews;
        
        // Aspectos mais comentados
        arsort($aspectosMencionados);
        $aspectosTop = array_filter($aspectosMencionados, fn($v) => $v > 0);
        
        // Top palavras-chave
        arsort($palavrasRelevantes);
        $topPalavras = array_slice(array_keys($palavrasRelevantes), 0, 8);
        
        // ===== GERAÇÃO DE TEXTO COM NLG =====
        $paragrafos = [];
        
        // === PARÁGRAFO 1: ABERTURA CONTEXTUALIZADA ===
        $intro = self::gerarIntroducao($totalReviews, $avgRating, $polarizacao, $ratingsCount);
        $paragrafos[] = $intro;
        
        // === PARÁGRAFO 2: ANÁLISE DE PONTOS FORTES ===
        if ($sentiments['POSITIVO'] > 0) {
            $analisePositiva = self::gerarAnalisePositiva(
                $sentiments['POSITIVO'],
                $percPositivos,
                $totalReviews,
                $topPalavras,
                $aspectosTop
            );
            $paragrafos[] = $analisePositiva;
        }
        
        // === PARÁGRAFO 3: ANÁLISE DE CRÍTICAS ===
        if ($sentiments['NEGATIVO'] > 0) {
            $analiseCriticas = self::gerarAnaliseCriticas(
                $sentiments['NEGATIVO'],
                $percNegativos,
                $totalReviews,
                $topPalavras,
                $aspectosTop,
                $avgRating
            );
            $paragrafos[] = $analiseCriticas;
        }
        
        // === PARÁGRAFO 4: CONTEXTO INTERMEDIÁRIO (se houver neutros) ===
        if ($percNeutros >= 20) {
            $analiseNeutros = self::gerarAnaliseNeutros($sentiments['NEUTRO'], $percNeutros);
            $paragrafos[] = $analiseNeutros;
        }
        
        // === PARÁGRAFO 5: CONCLUSÃO E RECOMENDAÇÃO ===
        $conclusao = self::gerarConclusao(
            $avgRating,
            $percPositivos,
            $percNegativos,
            $polarizacao,
            $totalReviews,
            $aspectosTop
        );
        $paragrafos[] = $conclusao;
        
        return implode("\n\n", $paragrafos);
    }
    
    /**
     * Gera introdução contextualizada
     */
    private static function gerarIntroducao($total, $media, $polarizacao, $ratings)
    {
        // Selecionar variação baseada na quantidade
        $variacaoEscolhida = '';
        
        if ($total == 1) {
            $opcoes = ["Este livro conta com uma avaliação inicial", "Uma única review foi registrada para este livro"];
            $variacaoEscolhida = $opcoes[array_rand($opcoes)];
        } elseif ($total <= 5) {
            $opcoes = [
                "Este título recebeu suas primeiras {total} avaliações de leitores",
                "Com {total} avaliações iniciais, este livro começa a formar sua reputação",
                "Baseado em {total} opiniões de leitores pioneiros"
            ];
            $variacaoEscolhida = $opcoes[array_rand($opcoes)];
        } elseif ($total <= 15) {
            $opcoes = [
                "Acumulando {total} avaliações, este livro apresenta",
                "Com um conjunto de {total} reviews, a obra revela",
                "Através de {total} avaliações de leitores"
            ];
            $variacaoEscolhida = $opcoes[array_rand($opcoes)];
        } elseif ($total <= 50) {
            $opcoes = [
                "Este livro consolidou {total} avaliações de leitores, alcançando",
                "Baseado em uma amostra sólida de {total} reviews",
                "Com {total} opiniões registradas, o livro demonstra"
            ];
            $variacaoEscolhida = $opcoes[array_rand($opcoes)];
        } else {
            $opcoes = [
                "Com expressivas {total} avaliações, este título conquistou",
                "Acumulando {total} reviews de leitores, a obra mantém",
                "Sustentado por {total} avaliações, o livro apresenta"
            ];
            $variacaoEscolhida = $opcoes[array_rand($opcoes)];
        }
        
        $intro = str_replace('{total}', $total, $variacaoEscolhida) . " uma média de {$media} estrelas. ";
        
        // Interpretação da média com variações
        if ($media >= 4.8) {
            $interpretacoes = [
                "A pontuação praticamente perfeita reflete uma obra excepcional que transcendeu expectativas.",
                "Uma avaliação sublime que coloca este título entre os mais aclamados do gênero.",
                "A nota extraordinária demonstra consenso absoluto sobre a qualidade superior da obra."
            ];
            $intro .= $interpretacoes[array_rand($interpretacoes)];
        } elseif ($media >= 4.5) {
            $interpretacoes = [
                "A avaliação excepcional indica uma obra de alta qualidade que conquistou amplamente seu público.",
                "Esta nota revela um livro que superou expectativas e entregou experiências memoráveis.",
                "A pontuação elevada evidencia excelência consistente reconhecida pelos leitores."
            ];
            $intro .= $interpretacoes[array_rand($interpretacoes)];
        } elseif ($media >= 4.0) {
            if ($polarizacao > 0.6) {
                $intro .= "A nota muito positiva indica forte aceitação, embora a distribuição de avaliações sugira que a obra ressoa de formas distintas com diferentes perfis de leitores.";
            } else {
                $interpretacoes = [
                    "Uma média muito positiva que demonstra qualidade consistente e boa entrega ao público-alvo.",
                    "Esta avaliação reflete um trabalho bem-executado que atende satisfatoriamente as expectativas.",
                    "A pontuação sólida evidencia uma obra competente que agrada a maioria dos leitores."
                ];
                $intro .= $interpretacoes[array_rand($interpretacoes)];
            }
        } elseif ($media >= 3.5) {
            if ($polarizacao > 0.5) {
                $intro .= "A média positiva vem acompanhada de opiniões divididas: enquanto alguns leitores encontraram grande valor, outros identificaram limitações significativas.";
            } else {
                $intro .= "Uma recepção positiva na média, sinalizando um livro com qualidades apreciáveis, porém com aspectos que poderiam ser aprimorados.";
            }
        } elseif ($media >= 3.0) {
            $intro .= "As avaliações revelam uma obra que divide opiniões. A média equilibrada reflete tanto elogios quanto críticas substanciais, sugerindo que a experiência varia consideravelmente entre leitores.";
        } elseif ($media >= 2.5) {
            $intro .= "A pontuação abaixo da média indica que o livro enfrentou dificuldades em atender expectativas. As críticas superam os elogios, sinalizando problemas que impactaram negativamente a experiência de leitura.";
        } else {
            $intro .= "A avaliação baixa revela sérias dificuldades identificadas pelos leitores. A recepção predominantemente negativa sugere que a obra não conseguiu entregar uma experiência satisfatória.";
        }
        
        return $intro;
    }
    
    /**
     * Gera análise dos pontos positivos
     */
    private static function gerarAnalisePositiva($qtdPos, $percPos, $total, $palavras, $aspectos)
    {
        $plural = $qtdPos === 1 ? 'leitor expressou' : 'leitores expressaram';
        
        // Variações de intensidade
        if ($percPos >= 85) {
            $intensidade = [
                "A esmagadora maioria das avaliações ({$qtdPos} {$plural} sentimentos positivos - {$percPos}%)",
                "Praticamente unânime, a recepção positiva ({$qtdPos} {$plural} - {$percPos}%)",
                "Com impressionantes {$percPos}% de aprovação ({$qtdPos} {$plural})"
            ];
        } elseif ($percPos >= 65) {
            $intensidade = [
                "A maior parte dos leitores ({$qtdPos} {$plural} - {$percPos}%)",
                "Uma sólida maioria ({$qtdPos} {$plural} sentimentos positivos - {$percPos}%)",
                "Grande parte das avaliações ({$qtdPos} {$plural} - {$percPos}%)"
            ];
        } elseif ($percPos >= 45) {
            $intensidade = [
                "Uma parcela considerável de leitores ({$qtdPos} {$plural} - {$percPos}%)",
                "Aproximadamente metade das avaliações ({$qtdPos} {$plural} - {$percPos}%)",
                "Um grupo expressivo ({$qtdPos} {$plural} sentimentos positivos - {$percPos}%)"
            ];
        } else {
            $intensidade = [
                "Alguns leitores ({$qtdPos} {$plural} - {$percPos}%)",
                "Uma minoria das avaliações ({$qtdPos} {$plural} - {$percPos}%)",
                "Parte do público ({$qtdPos} {$plural} sentimentos positivos - {$percPos}%)"
            ];
        }
        
        $texto = $intensidade[array_rand($intensidade)];
        
        // Mencionar aspectos específicos
        $aspectosPositivos = [];
        foreach ($aspectos as $asp => $count) {
            if ($count >= 2) {
                $aspectosPositivos[] = $asp;
            }
        }
        
        if (count($aspectosPositivos) >= 2) {
            $texto .= " destaca especialmente " . implode(', ', array_slice($aspectosPositivos, 0, 2));
            $texto .= " como pontos fortes da obra";
        } elseif (count($palavras) >= 3) {
            $palavrasDestaque = array_slice($palavras, 0, 3);
            $texto .= " menciona recorrentemente termos como \"" . implode('", "', $palavrasDestaque) . "\" em suas análises";
        } else {
            $texto .= " elogia diferentes aspectos da narrativa";
        }
        
        $texto .= ". ";
        
        // Conclusão contextual
        if ($percPos >= 75) {
            $conclusoes = [
                "Este consenso amplamente favorável demonstra que o livro consegue entregar uma experiência envolvente e satisfatória para a grande maioria de seu público-alvo.",
                "A aprovação massiva indica que a obra possui qualidades consistentes que ressoam fortemente com os leitores.",
                "O alto índice de satisfação sugere que o livro atinge seus objetivos narrativos de forma eficaz e memorável."
            ];
        } elseif ($percPos >= 55) {
            $conclusoes = [
                "Os leitores que apreciaram o livro encontraram valor genuíno em seus elementos, embora reconheçam que nem todos os aspectos funcionam igualmente bem.",
                "Para este grupo, os pontos fortes identificados foram suficientes para proporcionar uma experiência de leitura gratificante.",
                "A satisfação deste público demonstra que, quando a obra acerta, consegue criar conexões significativas com os leitores."
            ];
        } else {
            $conclusoes = [
                "Para estes leitores, os aspectos positivos identificados foram capazes de compensar as limitações percebidas.",
                "Este grupo conseguiu extrair valor e apreciação mesmo diante de imperfeições identificadas na obra."
            ];
        }
        
        $texto .= $conclusoes[array_rand($conclusoes)];
        
        return $texto;
    }
    
    /**
     * Gera análise das críticas
     */
    private static function gerarAnaliseCriticas($qtdNeg, $percNeg, $total, $palavras, $aspectos, $media)
    {
        $plural = $qtdNeg === 1 ? 'avaliação manifestou' : 'avaliações manifestaram';
        
        // Transição contextual
        if ($percNeg >= 60) {
            $transicoes = [
                "Por outro lado, a maioria das avaliações ({$qtdNeg} {$plural} - {$percNeg}%)",
                "Contudo, uma parte significativa ({$qtdNeg} {$plural} insatisfação - {$percNeg}%)",
                "No entanto, o volume de críticas é considerável ({$qtdNeg} {$plural} - {$percNeg}%)"
            ];
        } elseif ($percNeg >= 35) {
            $transicoes = [
                "Por outro lado, uma parcela relevante ({$qtdNeg} {$plural} - {$percNeg}%)",
                "Ainda assim, parte significativa dos leitores ({$qtdNeg} {$plural} críticas - {$percNeg}%)",
                "Contudo, não é desprezível o número de avaliações negativas ({$qtdNeg} {$plural} - {$percNeg}%)"
            ];
        } else {
            $transicoes = [
                "Ainda assim, algumas avaliações ({$qtdNeg} {$plural} - {$percNeg}%)",
                "Por outro lado, uma minoria ({$qtdNeg} {$plural} ressalvas - {$percNeg}%)",
                "Contudo, existem críticas ({$qtdNeg} {$plural} - {$percNeg}%)"
            ];
        }
        
        $texto = $transicoes[array_rand($transicoes)];
        
        // Identificar padrões nas críticas
        $criticasComuns = [];
        $palavrasNegativas = ['confuso', 'lento', 'fraco', 'superficial', 'previsível', 'arrastado', 'repetitivo'];
        foreach ($palavrasNegativas as $neg) {
            if (in_array($neg, $palavras)) {
                $criticasComuns[] = $neg;
            }
        }
        
        if (!empty($criticasComuns)) {
            $texto .= " aponta problemas relacionados a aspectos como " . implode(' e ', array_slice($criticasComuns, 0, 2));
        } else {
            $texto .= " identifica limitações em elementos da obra";
        }
        
        $texto .= ". ";
        
        // Recomendação baseada no contexto
        if ($percNeg >= 65) {
            $recomendacoes = [
                "O volume expressivo de insatisfação é um sinal de alerta importante. Recomenda-se fortemente analisar as críticas específicas para avaliar se os problemas mencionados são incompatíveis com suas expectativas.",
                "A predominância de avaliações negativas sugere dificuldades substanciais na execução da obra. Prudência ao considerar a compra, especialmente se os pontos criticados forem relevantes para seu perfil de leitor.",
                "As críticas em maioria indicam que o livro pode frustrar expectativas. Avaliar cuidadosamente se os problemas apontados são dealbreakers para você é essencial antes da decisão."
            ];
        } elseif ($percNeg >= 40) {
            $recomendacoes = [
                "Essas críticas revelam que a obra não funciona uniformemente para todos os perfis. Considerar se os aspectos negativos mencionados alinham-se com suas sensibilidades como leitor é fundamental.",
                "A presença significativa de avaliações críticas sugere que a experiência pode variar bastante. Recomenda-se ponderar se os problemas identificados afetam elementos que você valoriza em uma leitura.",
                "As ressalvas levantadas merecem atenção especial. Entender o contexto dessas críticas pode ajudar a determinar se o livro é adequado para suas preferências específicas."
            ];
        } else {
            $recomendacoes = [
                "Embora minoritárias, essas observações podem ser especialmente relevantes para leitores com expectativas ou preferências muito específicas que se alinhem com os pontos criticados.",
                "As críticas parecem representar casos particulares ou sensibilidades individuais, não afetando substancialmente a percepção geral positiva da obra.",
                "Este grupo de leitores identificou aspectos que não funcionaram em sua experiência, mas tais pontos não parecem ser sistemáticos ou generalizados."
            ];
        }
        
        $texto .= $recomendacoes[array_rand($recomendacoes)];
        
        return $texto;
    }
    
    /**
     * Gera análise dos neutros (quando relevante)
     */
    private static function gerarAnaliseNeutros($qtdNeutro, $percNeutro)
    {
        $plural = $qtdNeutro === 1 ? 'avaliação apresentou' : 'avaliações apresentaram';
        
        $variacoes = [
            "Adicionalmente, {$qtdNeutro} {$plural} uma postura neutra ({$percNeutro}%), reconhecendo tanto qualidades quanto limitações sem inclinar-se decisivamente para nenhum lado. Esta parcela do público parece ter tido uma experiência equilibrada, onde aspectos positivos e negativos se compensaram.",
            "Vale mencionar que {$qtdNeutro} {$plural} sentimentos neutros ({$percNeutro}%), sugerindo uma experiência de leitura que, embora competente, não gerou fortes emoções ou impressões duradouras em nenhuma direção.",
            "Interessante notar que {$qtdNeutro} {$plural} posicionamento neutro ({$percNeutro}%), indicando leitores que encontraram uma obra funcional e adequada, mas sem elementos extraordinários que justificassem entusiasmo especial."
        ];
        
        return $variacoes[array_rand($variacoes)];
    }
    
    /**
     * Gera conclusão e recomendação final
     */
    private static function gerarConclusao($media, $percPos, $percNeg, $polarizacao, $total, $aspectos)
    {
        // Análise multifatorial
        $pontuacao = 0;
        
        if ($media >= 4.5) $pontuacao += 5;
        elseif ($media >= 4.0) $pontuacao += 4;
        elseif ($media >= 3.5) $pontuacao += 3;
        elseif ($media >= 3.0) $pontuacao += 2;
        else $pontuacao += 1;
        
        if ($percPos >= 75) $pontuacao += 3;
        elseif ($percPos >= 60) $pontuacao += 2;
        elseif ($percPos >= 45) $pontuacao += 1;
        
        if ($polarizacao < 0.3) $pontuacao += 1; // Consenso
        if ($total >= 20) $pontuacao += 1; // Amostra significativa
        
        // Gerar conclusão baseada na pontuação total
        if ($pontuacao >= 9) {
            $conclusoes = [
                "Em síntese, as avaliações convergem de forma consistente para uma recomendação entusiasta. O livro demonstra excelência em múltiplos aspectos e estabelece conexão genuína com seu público. Se o tema e gênero despertam seu interesse, há elevada probabilidade de você também ter uma experiência excepcional com esta leitura.",
                "O panorama geral das avaliações é inequivocamente positivo. A obra entrega qualidade superior de forma consistente, justificando plenamente o investimento de tempo e atenção. Trata-se de uma aposta segura para leitores que apreciam o gênero.",
                "As evidências convergem para classificar este como um título de destaque em seu segmento. A satisfação generalizada dos leitores, combinada com a média elevada, sugere uma experiência de leitura memorável e gratificante para a ampla maioria do público-alvo."
            ];
        } elseif ($pontuacao >= 7) {
            $conclusoes = [
                "De modo geral, o balanço das avaliações pende positivamente. O livro apresenta mais acertos do que falhas e tende a satisfazer leitores que buscam " . ($media >= 4.2 ? "qualidade acima da média" : "uma experiência sólida") . " no gênero. Considere seus gostos pessoais em relação aos aspectos destacados nas reviews.",
                "O panorama favorável sugere uma obra competente que cumpre o que promete. Embora possa não revolucionar o gênero, entrega uma experiência consistente e satisfatória. Vale a pena para quem busca uma leitura confiável na temática.",
                "As avaliações apontam para um livro bem-executado, com qualidades que superam as eventuais limitações. A recepção majoritariamente positiva indica que a maioria dos leitores sai satisfeita da experiência, tornando-o uma escolha razoavelmente segura."
            ];
        } elseif ($pontuacao >= 5) {
            $conclusoes = [
                "O quadro geral revela uma obra com méritos apreciáveis, porém não isenta de problemas. Para uma decisão informada, recomenda-se pesar cuidadosamente tanto os elogios quanto as críticas, priorizando os aspectos que mais importam para sua experiência de leitura ideal.",
                "As avaliações sugerem um livro que funciona para alguns perfis mas não para outros. A chave está em identificar se os elementos elogiados alinham-se com suas preferências e se as críticas apontam problemas que você considera toleráveis ou significativos.",
                "A recepção mista indica que sua experiência com este livro dependerá fortemente de expectativas e preferências pessoais. Recomenda-se investigar especificamente se os aspectos criticados são dealbreakers para seu perfil de leitor."
            ];
        } elseif ($pontuacao >= 3) {
            $conclusoes = [
                "As avaliações revelam uma obra significativamente polarizadora. Há leitores satisfeitos, mas também críticas substanciais que não podem ser ignoradas. A decisão de compra deve considerar criteriosamente seu perfil específico e tolerância aos problemas apontados.",
                "O cenário dividido sugere cautela. Enquanto alguns encontraram valor, outros ficaram decepcionados com aspectos fundamentais. Recomenda-se análise aprofundada das reviews detalhadas para formar expectativas realistas antes de investir na leitura.",
                "As opiniões divergentes indicam que este livro pode tanto agradar quanto decepcionar, dependendo de expectativas individuais. Sugere-se leitura atenta tanto de avaliações positivas quanto negativas para uma decisão bem fundamentada."
            ];
        } else {
            $conclusoes = [
                "As avaliações, predominantemente críticas, sugerem que o livro enfrentou dificuldades significativas em satisfazer seu público. Recomenda-se cautela e análise minuciosa das críticas específicas antes de decidir pela aquisição, especialmente verificando se os problemas centrais são aceitáveis para seu perfil.",
                "A recepção majoritariamente negativa é um sinal de alerta que merece atenção. Os problemas identificados pelos leitores parecem substanciais e recorrentes. Considere cuidadosamente se está disposto a correr o risco diante das críticas apresentadas.",
                "O panorama crítico indica que a obra não conseguiu entregar uma experiência satisfatória para a maioria de seus leitores. A menos que os aspectos negativos mencionados não sejam relevantes para você, há alternativas potencialmente mais gratificantes no mesmo gênero."
            ];
        }
        
        
        return $conclusoes[array_rand($conclusoes)];
    }

    /**
     * Retorna ícone e cor baseado no sentimento
     */
    public static function getSentimentIcon($sentiment)
    {
        $icons = [
            'POSITIVO' => ['icon' => 'fas fa-smile', 'color' => '#10b981', 'text' => 'Positivo'],
            'NEGATIVO' => ['icon' => 'fas fa-frown', 'color' => '#ef4444', 'text' => 'Negativo'],
            'NEUTRO' => ['icon' => 'fas fa-meh', 'color' => '#f59e0b', 'text' => 'Neutro']
        ];
        
        return $icons[$sentiment] ?? $icons['NEUTRO'];
    }
}
