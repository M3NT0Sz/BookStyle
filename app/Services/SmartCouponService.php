<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmartCouponService
{
    /**
     * Detecta primeiro pedido e gera cupom de boas-vindas
     */
    public static function handleFirstPurchase($userId)
    {
        // Verificar se é realmente o primeiro pedido usando PDO
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM orders WHERE user_id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        $orderCount = $result['count'];
        
        if ($orderCount === 1) {
            // Verificar se já não existe um cupom de primeiro pedido
            $stmt2 = $pdo->prepare("
                SELECT id FROM coupons 
                WHERE user_id = ? 
                AND trigger_type = 'first_purchase'
            ");
            $stmt2->execute([$userId]);
            $existingCoupon = $stmt2->fetch();
            
            if (!$existingCoupon) {
                $couponId = Coupon::createSmartCoupon('first_purchase', $userId, [
                    'message' => 'Obrigado pela sua primeira compra! Aqui está um desconto especial para você.'
                ]);
                
                // Enviar notificação
                self::sendCouponNotification($userId, $couponId, 'Parabéns! Você ganhou um cupom de desconto!');
                
                return $couponId;
            }
        }
        
        return null;
    }

    /**
     * Detecta abandono de carrinho e gera cupom de retorno
     */
    public static function handleCartAbandonment($userId)
    {
        // Buscar itens do carrinho do usuário
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            SELECT ci.quantity, b.price 
            FROM cart_items ci 
            JOIN books b ON ci.book_id = b.id 
            WHERE ci.user_id = ?
        ");
        $stmt->execute([$userId]);
        $cartItems = $stmt->fetchAll();
        
        if (!empty($cartItems) && count($cartItems) > 0) {
            // Verificar se já não existe cupom recente de abandono
            $stmt2 = $pdo->prepare("
                SELECT id FROM coupons 
                WHERE user_id = ? 
                AND trigger_type = 'cart_abandonment'
                AND created_at >= ?
            ");
            $stmt2->execute([$userId, date('Y-m-d H:i:s', strtotime('-7 days'))]);
            $recentCoupon = $stmt2->fetch();
            
            if (!$recentCoupon) {
                $totalValue = array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $cartItems));
                
                // Só gerar cupom se o carrinho tiver valor significativo
                if ($totalValue >= 30) {
                    $couponId = Coupon::createSmartCoupon('cart_abandonment', $userId, [
                        'message' => 'Não se esqueça dos seus livros! Use este cupom para finalizar sua compra.',
                        'abandoned_value' => $totalValue
                    ]);
                    
                    // Enviar notificação
                    self::sendCouponNotification($userId, $couponId, 'Volte e ganhe desconto nos livros que você escolheu!');
                    
                    return $couponId;
                }
            }
        }
        
        return null;
    }

    /**
     * Gera cupom de aniversário
     */
    public static function handleBirthday($userId)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if ($user && isset($user['birth_date']) && $user['birth_date']) {
            $today = date('m-d');
            $birthDate = date('m-d', strtotime($user['birth_date']));
            
            if ($today === $birthDate) {
                // Verificar se já não gerou cupom este ano
                $pdo2 = \App\Models\DatabaseSingleton::getInstance()->getConnection();
                $stmt2 = $pdo2->prepare("
                    SELECT id FROM coupons 
                    WHERE user_id = ? 
                    AND trigger_type = 'birthday' 
                    AND created_at >= ?
                ");
                $stmt2->execute([$userId, date('Y-01-01')]);
                $yearlyBirthdayCoupon = $stmt2->fetch();
                
                if (!$yearlyBirthdayCoupon) {
                    $couponId = Coupon::createSmartCoupon('birthday', $userId, [
                        'message' => 'Feliz aniversário! Comemore conosco com este desconto especial.'
                    ]);
                    
                    // Enviar notificação
                    self::sendCouponNotification($userId, $couponId, '🎉 Feliz Aniversário! Seu presente está aqui!');
                    
                    return $couponId;
                }
            }
        }
        
        return null;
    }

    /**
     * Gera cupom baseado em gênero preferido
     */
    public static function handleGenreBasedCoupon($userId, $preferredGenre)
    {
        // Verificar se já não existe cupom recente para este gênero
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            SELECT id FROM coupons 
            WHERE user_id = ? 
            AND trigger_type = 'genre_based'
            AND applicable_genres LIKE ?
            AND created_at >= ?
        ");
        $stmt->execute([
            $userId, 
            '%' . $preferredGenre . '%', 
            date('Y-m-d H:i:s', strtotime('-30 days'))
        ]);
        $recentGenreCoupon = $stmt->fetch();
        
        if (!$recentGenreCoupon) {
            $couponId = Coupon::createSmartCoupon('genre_based', $userId, [
                'applicable_genres' => [$preferredGenre],
                'message' => 'Descobrimos que você ama ' . ucfirst($preferredGenre) . '! Aqui está um desconto especial.'
            ]);
            
            // Enviar notificação
            self::sendCouponNotification($userId, $couponId, 'Desconto especial para livros de ' . ucfirst($preferredGenre) . '!');
            
            return $couponId;
        }
        
        return null;
    }

    /**
     * Gera cupom de fidelidade baseado em número de pedidos
     */
    public static function handleLoyaltyCoupon($userId)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count FROM orders 
            WHERE user_id = ? AND status != 'cancelled'
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        $orderCount = $result['count'];
        
        // Marcos de fidelidade: 5, 10, 20, 50 pedidos
        $loyaltyMilestones = [5, 10, 20, 50];
        
        foreach ($loyaltyMilestones as $milestone) {
            if ($orderCount == $milestone) {
                // Verificar se já não deu cupom para este marco
                $stmt2 = $pdo->prepare("
                    SELECT id FROM coupons 
                    WHERE user_id = ? 
                    AND trigger_type = 'loyalty'
                    AND trigger_conditions LIKE ?
                ");
                $stmt2->execute([$userId, '%"milestone":' . $milestone . '%']);
                $existingLoyaltyCoupon = $stmt2->fetch();
                
                if (!$existingLoyaltyCoupon) {
                    $discountValue = match($milestone) {
                        5 => 15,
                        10 => 20,
                        20 => 25,
                        50 => 30,
                        default => 10
                    };
                    
                    $couponId = Coupon::createSmartCoupon('loyalty', $userId, [
                        'discount' => $discountValue,
                        'milestone' => $milestone,
                        'message' => 'Parabéns por ser um cliente fiel! Você chegou ao marco de ' . $milestone . ' pedidos!'
                    ]);
                    
                    // Enviar notificação
                    self::sendCouponNotification($userId, $couponId, '🏆 Cliente Fiel! Você ganhou um cupom especial!');
                    
                    return $couponId;
                }
            }
        }
        
        return null;
    }

    /**
     * Detecta carrinho de alto valor e oferece desconto
     */
    public static function handleHighValueCart($userId, $cartTotal)
    {
        // Só para carrinhos acima de R$ 200
        if ($cartTotal >= 200) {
            // Verificar se já não tem cupom de alto valor recente
            $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
            $stmt = $pdo->prepare("
                SELECT id FROM coupons 
                WHERE user_id = ? 
                AND trigger_type = 'high_value_cart'
                AND created_at >= ?
            ");
            $stmt->execute([$userId, date('Y-m-d H:i:s', strtotime('-24 hours'))]);
            $recentHighValueCoupon = $stmt->fetch();
            
            if (!$recentHighValueCoupon) {
                $couponId = Coupon::createSmartCoupon('high_value_cart', $userId, [
                    'cart_value' => $cartTotal,
                    'message' => 'Carrinho VIP detectado! Ganhe desconto extra nesta compra especial.'
                ]);
                
                // Enviar notificação
                self::sendCouponNotification($userId, $couponId, '💎 Carrinho VIP! Desconto especial disponível!');
                
                return $couponId;
            }
        }
        
        return null;
    }

    /**
     * Analisa comportamento do usuário e sugere cupons apropriados
     */
    public static function analyzeBehaviorAndSuggestCoupons($userId)
    {
        $suggestions = [];
        
        // Analisar gêneros preferidos baseado em pedidos anteriores
        $preferredGenres = self::getPreferredGenres($userId);
        
        foreach ($preferredGenres as $genre) {
            if (self::handleGenreBasedCoupon($userId, $genre)) {
                $suggestions[] = "Cupom de {$genre} criado";
            }
        }
        
        // Verificar marcos de fidelidade
        if (self::handleLoyaltyCoupon($userId)) {
            $suggestions[] = "Cupom de fidelidade criado";
        }
        
        // Verificar aniversário
        if (self::handleBirthday($userId)) {
            $suggestions[] = "Cupom de aniversário criado";
        }
        
        return $suggestions;
    }

    /**
     * Obtém os gêneros preferidos de um usuário baseado no histórico
     */
    private static function getPreferredGenres($userId)
    {
        // Buscar gêneros dos livros mais comprados pelo usuário
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        $sql = "SELECT b.genre, COUNT(*) as frequency 
                FROM orders o 
                JOIN order_items oi ON o.id = oi.order_id 
                JOIN books b ON oi.book_id = b.id 
                WHERE o.user_id = ? AND o.status != 'cancelled'
                GROUP BY b.genre 
                ORDER BY frequency DESC 
                LIMIT 3";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        
        $results = $stmt->fetchAll();
        return array_column($results, 'genre');
    }

    /**
     * Envia notificação sobre novo cupom (placeholder para futuro sistema)
     */
    private static function sendCouponNotification($userId, $couponId, $message)
    {
        // Por enquanto, apenas log
        // Futuramente pode integrar com sistema de notificações/email
        \Illuminate\Support\Facades\Log::info("Cupom automático criado para usuário {$userId}: {$message}");
        
        // Adicionar à sessão para mostrar na próxima página carregada
        if (Auth::check() && Auth::id() == $userId) {
            session()->flash('new_coupon', [
                'coupon_id' => $couponId,
                'message' => $message
            ]);
        }
    }

    /**
     * Obtém cupons sugeridos para o usuário baseado no contexto atual
     */
    public static function getSuggestedCoupons($userId, $cartItems = [])
    {
        $availableCoupons = Coupon::getAvailableCouponsForUser($userId);
        $suggestions = [];
        
        foreach ($availableCoupons as $coupon) {
            $validation = Coupon::isValidForUser($coupon, $userId, $cartItems);
            
            if ($validation['valid']) {
                $suggestions[] = [
                    'coupon' => $coupon,
                    'priority' => self::calculatePriority($coupon, $cartItems),
                    'message' => self::getPersonalizedMessage($coupon, $cartItems)
                ];
            }
        }
        
        // Ordenar por prioridade
        usort($suggestions, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });
        
        return array_slice($suggestions, 0, 3); // Máximo 3 sugestões
    }

    /**
     * Calcula prioridade de sugestão do cupom
     */
    private static function calculatePriority($coupon, $cartItems)
    {
        $priority = 0;
        
        // Prioridade por tipo de trigger
        $triggerPriorities = [
            'high_value_cart' => 100,
            'birthday' => 90,
            'loyalty' => 80,
            'first_purchase' => 70,
            'genre_based' => 60,
            'cart_abandonment' => 50,
            'manual' => 30
        ];
        
        $priority += $triggerPriorities[$coupon['trigger_type']] ?? 20;
        
        // Prioridade por valor do desconto
        $priority += $coupon['discount'] * 2;
        
        // Prioridade por proximidade de expiração
        if ($coupon['expires_at']) {
            $daysToExpire = (strtotime($coupon['expires_at']) - time()) / 86400;
            if ($daysToExpire <= 7) {
                $priority += 30; // Urgência
            }
        }
        
        return $priority;
    }

    /**
     * Gera mensagem personalizada para o cupom
     */
    private static function getPersonalizedMessage($coupon, $cartItems)
    {
        $messages = [
            'first_purchase' => '🎉 Bem-vindo! Use este cupom na sua primeira compra!',
            'cart_abandonment' => '⏰ Não deixe seus livros escaparem! Finalize agora com desconto!',
            'birthday' => '🎂 Feliz aniversário! Seu presente especial está aqui!',
            'genre_based' => '📚 Desconto especial nos seus gêneros favoritos!',
            'loyalty' => '🏆 Cliente fiel merece desconto especial!',
            'high_value_cart' => '💎 Carrinho VIP! Ganhe desconto extra agora!',
            'manual' => '🎁 Cupom especial disponível!'
        ];
        
        return $messages[$coupon['trigger_type']] ?? 'Cupom de desconto disponível!';
    }
}