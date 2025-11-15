<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'type',
        'expires_at',
        'trigger_type',
        'trigger_conditions',
        'user_id',
        'max_uses',
        'used_count',
        'minimum_cart_value',
        'applicable_genres',
        'is_active',
        'is_auto_generated',
        'generated_at',
        'last_used_at'
    ];

    protected $casts = [
        'expires_at' => 'date',
        'trigger_conditions' => 'array',
        'applicable_genres' => 'array',
        'is_active' => 'boolean',
        'is_auto_generated' => 'boolean',
        'generated_at' => 'datetime',
        'last_used_at' => 'datetime',
        'discount' => 'float',
        'minimum_cart_value' => 'float',
        'max_uses' => 'integer',
        'used_count' => 'integer',
    ];

    // Relacionamento com usuário
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com usuários que usaram o cupom
    public function usedByUsers()
    {
        return $this->belongsToMany(User::class, 'coupon_user')
            ->withPivot('order_id', 'used_at')
            ->withTimestamps();
    }

    // ============================================
    // MÉTODOS ESTÁTICOS PARA COMPATIBILIDADE
    // ============================================
    public static function getAllCoupons()
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->query('
            SELECT c.*, u.name as user_name 
            FROM coupons c 
            LEFT JOIN users u ON c.user_id = u.id 
            ORDER BY c.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public static function findCoupon($id)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM coupons WHERE id = ? AND is_active = 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByCode($code)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM coupons WHERE code = ? AND is_active = 1');
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    // ============================================
    // MÉTODOS INTELIGENTES
    // ============================================
    
    /**
     * Verifica se o cupom é válido para um usuário específico
     */
    public static function isValidForUser($couponData, $userId, $cartItems = [])
    {
        // Verificações básicas
        if (!$couponData || !$couponData['is_active']) {
            return ['valid' => false, 'message' => 'Cupom inativo ou não encontrado'];
        }

        // NOVO: Verificar se o usuário já usou este cupom
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $checkUsageStmt = $pdo->prepare("SELECT COUNT(*) FROM coupon_user WHERE coupon_id = ? AND user_id = ?");
        $checkUsageStmt->execute([$couponData['id'], $userId]);
        
        if ($checkUsageStmt->fetchColumn() > 0) {
            return ['valid' => false, 'message' => 'Você já usou este cupom anteriormente'];
        }

        // NOVO: Verificar cooldown APENAS para cupons automáticos (gerados por IA)
        if (isset($couponData['is_auto_generated']) && $couponData['is_auto_generated']) {
            $userStmt = $pdo->prepare("SELECT last_coupon_used_at FROM users WHERE id = ?");
            $userStmt->execute([$userId]);
            $userData = $userStmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($userData && $userData['last_coupon_used_at']) {
                $lastUsed = new \DateTime($userData['last_coupon_used_at']);
                $cooldownDays = 7; // 7 dias para cupons IA
                $nextAvailable = $lastUsed->modify("+{$cooldownDays} days");
                $now = new \DateTime();
                
                if ($now < $nextAvailable) {
                    $daysRemaining = $now->diff($nextAvailable)->days;
                    return ['valid' => false, 'message' => "Aguarde {$daysRemaining} dias para usar outro cupom automático"];
                }
            }
        }
        // Cupons criados por admin NÃO têm cooldown

        // Verificar expiração
        if ($couponData['expires_at'] && strtotime($couponData['expires_at']) < time()) {
            return ['valid' => false, 'message' => 'Cupom expirado'];
        }

        // Verificar limite de usos
        if ($couponData['max_uses'] && $couponData['used_count'] >= $couponData['max_uses']) {
            return ['valid' => false, 'message' => 'Cupom já foi usado o máximo de vezes'];
        }

        // Verificar se é específico para usuário
        if ($couponData['user_id'] && $couponData['user_id'] != $userId) {
            return ['valid' => false, 'message' => 'Este cupom é específico para outro usuário'];
        }

        // Verificar valor mínimo do carrinho
        if ($couponData['minimum_cart_value']) {
            $cartTotal = array_sum(array_map(function($item) {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            }, $cartItems));
            
            if ($cartTotal < $couponData['minimum_cart_value']) {
                return ['valid' => false, 'message' => "Valor mínimo do carrinho: R$ " . number_format($couponData['minimum_cart_value'], 2, ',', '.')];
            }
        }

        // Verificar gêneros aplicáveis
        if (!empty($couponData['applicable_genres'])) {
            $applicableGenres = is_string($couponData['applicable_genres']) 
                ? json_decode($couponData['applicable_genres'], true) 
                : $couponData['applicable_genres'];
            
            if (!empty($applicableGenres) && is_array($applicableGenres)) {
                // Normalizar gêneros aplicáveis (lowercase, sem hífens)
                $normalizedApplicableGenres = array_map(function($g) {
                    return strtolower(str_replace(['-', '_', ' '], '', trim($g)));
                }, $applicableGenres);
                
                // Buscar gêneros dos livros no carrinho
                $bookIds = array_column($cartItems, 'id');
                
                if (empty($bookIds)) {
                    return ['valid' => false, 'message' => 'Carrinho vazio'];
                }
                
                $placeholders = str_repeat('?,', count($bookIds) - 1) . '?';
                $stmt = $pdo->prepare("SELECT DISTINCT genre FROM books WHERE id IN ($placeholders)");
                $stmt->execute($bookIds);
                $cartGenres = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                
                // Verificar se pelo menos um livro do carrinho é do gênero aplicável
                $hasMatchingGenre = false;
                foreach ($cartGenres as $genre) {
                    // O gênero pode estar como JSON array ou string simples
                    $bookGenreList = [];
                    
                    if (is_string($genre)) {
                        // Tentar decodificar se for JSON
                        $decoded = json_decode($genre, true);
                        if (is_array($decoded)) {
                            $bookGenreList = $decoded;
                        } else {
                            $bookGenreList = [$genre];
                        }
                    } else {
                        $bookGenreList = is_array($genre) ? $genre : [$genre];
                    }
                    
                    // Normalizar gêneros do livro
                    foreach ($bookGenreList as $bookGenre) {
                        $normalizedBookGenre = strtolower(str_replace(['-', '_', ' '], '', trim($bookGenre)));
                        
                        if (in_array($normalizedBookGenre, $normalizedApplicableGenres)) {
                            $hasMatchingGenre = true;
                            break 2; // Sair dos dois loops
                        }
                    }
                }
                
                if (!$hasMatchingGenre) {
                    $genresStr = implode(', ', $applicableGenres);
                    return ['valid' => false, 'message' => "Este cupom só é válido para livros de: {$genresStr}"];
                }
            }
        }

        return ['valid' => true, 'message' => 'Cupom válido'];
    }

    /**
     * Gera um código único para o cupom
     */
    public static function generateUniqueCode($prefix = 'AUTO')
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        do {
            $code = $prefix . '-' . strtoupper(substr(uniqid(), -6)) . '-' . rand(10, 99);
            $stmt = $pdo->prepare('SELECT id FROM coupons WHERE code = ?');
            $stmt->execute([$code]);
        } while ($stmt->fetch());
        
        return $code;
    }

    /**
     * Cria um cupom inteligente baseado em trigger
     */
    public static function createSmartCoupon($triggerType, $userId, $conditions = [])
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        // Configurações padrão por tipo de trigger
        $defaults = self::getTriggerDefaults($triggerType);
        $couponData = array_merge($defaults, $conditions);
        
        // Gerar código único
        $couponData['code'] = self::generateUniqueCode($defaults['prefix'] ?? 'SMART');
        $couponData['user_id'] = $userId;
        $couponData['trigger_type'] = $triggerType;
        $couponData['is_auto_generated'] = true;
        $couponData['generated_at'] = date('Y-m-d H:i:s');
        $couponData['trigger_conditions'] = json_encode($conditions);

        $sql = "INSERT INTO coupons (
            code, discount, type, expires_at, trigger_type, trigger_conditions, 
            user_id, max_uses, minimum_cart_value, applicable_genres,
            is_active, is_auto_generated, generated_at, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $couponData['code'],
            $couponData['discount'],
            $couponData['type'],
            $couponData['expires_at'] ?? null,
            $couponData['trigger_type'],
            $couponData['trigger_conditions'],
            $couponData['user_id'],
            $couponData['max_uses'] ?? null,
            $couponData['minimum_cart_value'] ?? null,
            isset($couponData['applicable_genres']) ? json_encode($couponData['applicable_genres']) : null,
            true, // is_active
            true, // is_auto_generated
            $couponData['generated_at']
        ]);
        
        $couponId = $pdo->lastInsertId();
        
        // NOTIFICAR USUÁRIO VIA OBSERVER
        \App\Services\NotificationService::notifyCouponAvailable(
            $userId,
            $couponId,
            $couponData['code'],
            $couponData['discount'],
            $couponData['type']
        );
        
        return $couponId;
    }

    /**
     * Configurações padrão para cada tipo de trigger
     */
    private static function getTriggerDefaults($triggerType)
    {
        switch ($triggerType) {
            case 'first_purchase':
                return [
                    'prefix' => 'BEM-VINDO',
                    'discount' => 15,
                    'type' => 'percent',
                    'expires_at' => date('Y-m-d', strtotime('+30 days')),
                    'max_uses' => 1,
                    'minimum_cart_value' => 50.00
                ];
            
            case 'cart_abandonment':
                return [
                    'prefix' => 'VOLTA',
                    'discount' => 10,
                    'type' => 'percent',
                    'expires_at' => date('Y-m-d', strtotime('+7 days')),
                    'max_uses' => 1
                ];
            
            case 'birthday':
                return [
                    'prefix' => 'FELIZ-ANIV',
                    'discount' => 20,
                    'type' => 'percent',
                    'expires_at' => date('Y-m-d', strtotime('+30 days')),
                    'max_uses' => 1
                ];
            
            case 'genre_based':
                return [
                    'prefix' => 'GENERO',
                    'discount' => 12,
                    'type' => 'percent',
                    'expires_at' => date('Y-m-d', strtotime('+15 days')),
                    'max_uses' => 2
                ];
            
            case 'loyalty':
                return [
                    'prefix' => 'FIDELIDADE',
                    'discount' => 25,
                    'type' => 'percent',
                    'expires_at' => date('Y-m-d', strtotime('+45 days')),
                    'max_uses' => 1,
                    'minimum_cart_value' => 100.00
                ];
            
            case 'high_value_cart':
                return [
                    'prefix' => 'VIP',
                    'discount' => 30,
                    'type' => 'fixed',
                    'expires_at' => date('Y-m-d', strtotime('+24 hours')),
                    'max_uses' => 1,
                    'minimum_cart_value' => 200.00
                ];
            
            default:
                return [
                    'prefix' => 'SMART',
                    'discount' => 10,
                    'type' => 'percent',
                    'expires_at' => date('Y-m-d', strtotime('+7 days')),
                    'max_uses' => 1
                ];
        }
    }

    /**
     * Busca cupons disponíveis para um usuário
     */
    public static function getAvailableCouponsForUser($userId)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        $sql = "SELECT * FROM coupons 
                WHERE is_active = 1 
                AND (user_id = ? OR user_id IS NULL)
                AND (expires_at IS NULL OR expires_at >= CURDATE())
                AND (max_uses IS NULL OR used_count < max_uses)
                ORDER BY discount DESC, expires_at ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll();
    }

    /**
     * Busca cupons sugeridos inteligentes para um usuário
     */
    public static function getSuggestedCoupons($user, $cartTotal = 0)
    {
        if (!$user) {
            return [];
        }

        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $suggestions = [];
        
        // Buscar cupons específicos do usuário que estão ativos e NÃO foram usados
        // Para cupons automáticos, verificar cooldown de 7 dias
        $sql = "SELECT c.* FROM coupons c
                WHERE c.is_active = 1 
                AND c.user_id = ?
                AND (c.expires_at IS NULL OR c.expires_at >= CURDATE())
                AND (c.max_uses IS NULL OR c.used_count < c.max_uses)
                AND c.id NOT IN (
                    SELECT coupon_id FROM coupon_user WHERE user_id = ?
                )
                ORDER BY c.discount DESC
                LIMIT 3";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user->id, $user->id]);
        $userCoupons = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Verificar cooldown apenas para cupons automáticos
        $userStmt = $pdo->prepare("SELECT last_coupon_used_at FROM users WHERE id = ?");
        $userStmt->execute([$user->id]);
        $userData = $userStmt->fetch(\PDO::FETCH_ASSOC);
        $inAutoCooldown = false;
        
        if ($userData && $userData['last_coupon_used_at']) {
            $lastUsed = new \DateTime($userData['last_coupon_used_at']);
            $nextAvailable = clone $lastUsed;
            $nextAvailable->modify('+7 days');
            $now = new \DateTime();
            $inAutoCooldown = ($now < $nextAvailable);
        }
        
        foreach ($userCoupons as $coupon) {
            // Pular cupons automáticos se estiver em cooldown
            if ($coupon['is_auto_generated'] && $inAutoCooldown) {
                continue;
            }
            
            $message = '';
            $genreInfo = '';
            
            // Se o cupom tiver gêneros específicos, adiciona na mensagem
            if (!empty($coupon['applicable_genres'])) {
                $genres = is_string($coupon['applicable_genres']) 
                    ? json_decode($coupon['applicable_genres'], true) 
                    : $coupon['applicable_genres'];
                    
                if (!empty($genres) && is_array($genres)) {
                    // Formatar gêneros para exibição
                    $formattedGenres = array_map(function($genre) {
                        return ucwords(str_replace(['-', '_'], ' ', trim($genre)));
                    }, $genres);
                    $genreInfo = ' (válido para: ' . implode(', ', $formattedGenres) . ')';
                }
            }
            
            switch ($coupon['trigger_type']) {
                case 'first_purchase':
                    $message = 'Cupom de boas-vindas especial para sua primeira compra!' . $genreInfo;
                    break;
                case 'birthday':
                    $message = 'Feliz aniversário! Este cupom é especial para você.' . $genreInfo;
                    break;
                case 'loyalty':
                    $message = 'Obrigado por ser um cliente fiel! Aproveite este desconto exclusivo.' . $genreInfo;
                    break;
                case 'high_value_cart':
                    $message = 'Seu carrinho merece um desconto VIP!' . $genreInfo;
                    break;
                case 'genre_based':
                    $message = 'Desconto especial baseado no seu histórico de compras!' . $genreInfo;
                    break;
                default:
                    $message = 'Cupom especial disponível para você!' . $genreInfo;
            }
            
            // Decodificar applicable_genres para exibição
            if (isset($coupon['applicable_genres']) && is_string($coupon['applicable_genres'])) {
                $coupon['applicable_genres'] = json_decode($coupon['applicable_genres'], true);
            }
            
            $suggestions[] = [
                'coupon' => $coupon,
                'message' => $message
            ];
        }
        
        // Tentar criar cupom baseado na última compra se puder (verifica cooldown internamente)
        if (!$inAutoCooldown) {
            self::tryCreateGenreBasedCoupon($user->id);
        }
        
        return $suggestions;
    }

    /**
     * Tenta criar um cupom baseado no gênero da última compra do usuário
     */
    public static function tryCreateGenreBasedCoupon($userId)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        // Verificar se já existe cupom genre_based ativo para este usuário
        $checkStmt = $pdo->prepare("
            SELECT COUNT(*) FROM coupons 
            WHERE user_id = ? 
            AND trigger_type = 'genre_based' 
            AND is_active = 1 
            AND (expires_at IS NULL OR expires_at >= CURDATE())
        ");
        $checkStmt->execute([$userId]);
        
        if ($checkStmt->fetchColumn() > 0) {
            return; // Já tem cupom baseado em gênero
        }
        
        // Buscar gênero da última compra
        $genreStmt = $pdo->prepare("
            SELECT b.genre 
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN books b ON oi.book_id = b.id
            WHERE o.user_id = ?
            AND o.status = 'delivered'
            ORDER BY o.created_at DESC
            LIMIT 1
        ");
        $genreStmt->execute([$userId]);
        $lastGenre = $genreStmt->fetchColumn();
        
        if ($lastGenre) {
            // Se o gênero vier como JSON, decodificar primeiro
            if (is_string($lastGenre) && strpos($lastGenre, '[') === 0) {
                $genreArray = json_decode($lastGenre, true);
                $lastGenre = is_array($genreArray) ? $genreArray[0] : $lastGenre;
            }
            
            // Criar cupom baseado nesse gênero (passar como array simples)
            self::createSmartCoupon('genre_based', $userId, [
                'applicable_genres' => [$lastGenre],
                'discount' => 15,
                'type' => 'percent',
                'expires_at' => date('Y-m-d', strtotime('+30 days')),
                'max_uses' => 1,
                'minimum_cart_value' => 30.00
            ]);
        }
    }

    /**
     * Busca todos os cupons ativos (apenas os melhores de cada tipo)
     */
    public static function getActiveCoupons()
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        // Pega apenas o melhor cupom de cada tipo (maior desconto)
        // e limita a quantidade total de cupons exibidos
        $sql = "SELECT c.* FROM coupons c
                INNER JOIN (
                    SELECT 
                        COALESCE(trigger_type, 'general') as group_type,
                        type,
                        MAX(discount) as max_discount
                    FROM coupons 
                    WHERE is_active = 1 
                    AND user_id IS NULL
                    AND (expires_at IS NULL OR expires_at >= CURDATE())
                    AND (max_uses IS NULL OR used_count < max_uses)
                    GROUP BY COALESCE(trigger_type, 'general'), type
                ) best ON COALESCE(c.trigger_type, 'general') = best.group_type 
                         AND c.type = best.type 
                         AND c.discount = best.max_discount
                WHERE c.is_active = 1 
                AND c.user_id IS NULL
                AND (c.expires_at IS NULL OR c.expires_at >= CURDATE())
                AND (c.max_uses IS NULL OR c.used_count < c.max_uses)
                GROUP BY COALESCE(c.trigger_type, 'general'), c.type
                ORDER BY c.discount DESC, c.created_at DESC
                LIMIT 6";
        
        $stmt = $pdo->query($sql);
        $coupons = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Decodificar applicable_genres para exibição
        foreach ($coupons as &$coupon) {
            if (isset($coupon['applicable_genres']) && is_string($coupon['applicable_genres'])) {
                $coupon['applicable_genres'] = json_decode($coupon['applicable_genres'], true);
            }
        }
        
        return $coupons;
    }

    /**
     * Marca um cupom como usado
     */
    public static function markAsUsed($couponId)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        $stmt = $pdo->prepare('UPDATE coupons SET used_count = used_count + 1, last_used_at = NOW() WHERE id = ?');
        return $stmt->execute([$couponId]);
    }

    // Métodos de compatibilidade mantidos
    public static function createCoupon(array $data)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('INSERT INTO coupons (code, discount, type, expires_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['code'],
            $data['discount'],
            $data['type'],
            $data['expires_at'] ?? null
        ]);
        return $pdo->lastInsertId();
    }

    public static function updateCoupon($id, array $data)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('UPDATE coupons SET code = ?, discount = ?, type = ?, expires_at = ? WHERE id = ?');
        return $stmt->execute([
            $data['code'],
            $data['discount'],
            $data['type'],
            $data['expires_at'] ?? null,
            $id
        ]);
    }

    public static function deleteCoupon($id)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('UPDATE coupons SET is_active = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
