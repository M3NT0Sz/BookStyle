<?php

namespace App\Services;

use App\Observers\NotificationSubject;
use App\Observers\UserNotificationObserver;

/**
 * NotificationService - Serviço para gerenciar notificações
 * Implementa o padrão Observer/Subject
 */
class NotificationService
{
    private static ?NotificationSubject $subject = null;

    /**
     * Obter instância do Subject (Singleton)
     */
    private static function getSubject(): NotificationSubject
    {
        if (self::$subject === null) {
            self::$subject = new NotificationSubject();
            
            // Registrar observadores padrão
            self::$subject->attach(new UserNotificationObserver());
        }

        return self::$subject;
    }

    /**
     * Notificar criação de pedido
     */
    public static function notifyOrderCreated(int $userId, int $orderId, string $orderNumber, float $total): void
    {
        self::getSubject()->notify('order.created', [
            'user_id' => $userId,
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'total' => $total,
        ]);
    }

    /**
     * Notificar mudança de status do pedido
     */
    public static function notifyOrderStatusChanged(int $userId, int $orderId, string $orderNumber, string $oldStatus, string $newStatus): void
    {
        self::getSubject()->notify('order.status_changed', [
            'user_id' => $userId,
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Notificar novo cupom disponível
     */
    public static function notifyCouponAvailable(int $userId, int $couponId, string $code, float $discount, string $type): void
    {
        self::getSubject()->notify('coupon.available', [
            'user_id' => $userId,
            'coupon_id' => $couponId,
            'code' => $code,
            'discount' => $discount,
            'type' => $type,
        ]);
    }

    /**
     * Notificar nova avaliação recebida
     */
    public static function notifyReviewReceived(int $sellerId, int $reviewId, int $bookId, string $bookName, int $rating): void
    {
        self::getSubject()->notify('review.received', [
            'seller_id' => $sellerId,
            'review_id' => $reviewId,
            'book_id' => $bookId,
            'book_name' => $bookName,
            'rating' => $rating,
        ]);
    }

    /**
     * Notificar pedido entregue (solicitar avaliação)
     */
    public static function notifyOrderDelivered(int $userId, int $orderId, string $orderNumber): void
    {
        self::getSubject()->notify('order.delivered', [
            'user_id' => $userId,
            'order_id' => $orderId,
            'order_number' => $orderNumber,
        ]);
    }

    /**
     * Registrar observador customizado
     */
    public static function attachObserver(\App\Observers\ObserverInterface $observer): void
    {
        self::getSubject()->attach($observer);
    }

    /**
     * Remover observador
     */
    public static function detachObserver(\App\Observers\ObserverInterface $observer): void
    {
        self::getSubject()->detach($observer);
    }
}
