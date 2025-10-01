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
                return $item['price'] * $item['quantity'];
            }, $cartItems));
            
            if ($cartTotal < $couponData['minimum_cart_value']) {
                return ['valid' => false, 'message' => "Valor mínimo do carrinho: R$ " . number_format($couponData['minimum_cart_value'], 2, ',', '.')];
            }
        }

        // Verificar gêneros aplicáveis
        if (!empty($couponData['applicable_genres'])) {
            $applicableGenres = json_decode($couponData['applicable_genres'], true);
            $cartGenres = array_unique(array_column($cartItems, 'genre'));
            
            if (!array_intersect($cartGenres, $applicableGenres)) {
                return ['valid' => false, 'message' => 'Este cupom não se aplica aos gêneros dos livros no seu carrinho'];
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
        
        return $pdo->lastInsertId();
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
