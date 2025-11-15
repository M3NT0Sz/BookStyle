<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\User;

/**
 * UserNotificationObserver - Observer concreto
 * Cria notificações para o usuário quando eventos ocorrem
 */
class UserNotificationObserver implements ObserverInterface
{
    /**
     * Atualizar quando um evento ocorre
     */
    public function update(string $event, $data): void
    {
        switch ($event) {
            case 'order.created':
                $this->handleOrderCreated($data);
                break;
                
            case 'order.status_changed':
                $this->handleOrderStatusChanged($data);
                break;
                
            case 'coupon.available':
                $this->handleCouponAvailable($data);
                break;
                
            case 'review.received':
                $this->handleReviewReceived($data);
                break;
                
            case 'order.delivered':
                $this->handleOrderDelivered($data);
                break;
        }
    }

    /**
     * Notificar quando pedido é criado
     */
    private function handleOrderCreated($data): void
    {
        Notification::create([
            'user_id' => $data['user_id'],
            'type' => 'order_created',
            'title' => '🎉 Pedido realizado com sucesso!',
            'message' => "Seu pedido #{$data['order_number']} foi realizado com sucesso. Total: R$ " . number_format($data['total'], 2, ',', '.'),
            'data' => [
                'order_id' => $data['order_id'],
                'order_number' => $data['order_number'],
                'total' => $data['total'],
            ],
        ]);
    }

    /**
     * Notificar quando status do pedido muda
     */
    private function handleOrderStatusChanged($data): void
    {
        $messages = [
            'pending' => '⏳ Seu pedido está sendo processado',
            'processing' => '📦 Seu pedido está em preparação',
            'shipped' => '🚚 Seu pedido foi enviado!',
            'delivered' => '✅ Seu pedido foi entregue!',
            'cancelled' => '❌ Seu pedido foi cancelado',
        ];

        $descriptions = [
            'pending' => 'Estamos processando seu pagamento.',
            'processing' => 'Estamos preparando seus itens para envio.',
            'shipped' => 'Seu pedido está a caminho! Acompanhe a entrega.',
            'delivered' => 'Seu pedido foi entregue. Aproveite!',
            'cancelled' => 'Seu pedido foi cancelado. Entre em contato se tiver dúvidas.',
        ];

        Notification::create([
            'user_id' => $data['user_id'],
            'type' => 'order_status',
            'title' => $messages[$data['new_status']] ?? 'Status do pedido atualizado',
            'message' => "Pedido #{$data['order_number']}: " . ($descriptions[$data['new_status']] ?? 'Status atualizado'),
            'data' => [
                'order_id' => $data['order_id'],
                'order_number' => $data['order_number'],
                'old_status' => $data['old_status'],
                'new_status' => $data['new_status'],
            ],
        ]);
    }

    /**
     * Notificar quando novo cupom está disponível
     */
    private function handleCouponAvailable($data): void
    {
        Notification::create([
            'user_id' => $data['user_id'],
            'type' => 'coupon_available',
            'title' => '🎁 Novo cupom disponível!',
            'message' => "Um novo cupom de {$data['discount']}" . ($data['type'] == 'percent' ? '%' : 'R$') . " está disponível para você! Código: {$data['code']}",
            'data' => [
                'coupon_id' => $data['coupon_id'],
                'code' => $data['code'],
                'discount' => $data['discount'],
                'type' => $data['type'],
            ],
        ]);
    }

    /**
     * Notificar quando recebe avaliação em produto
     */
    private function handleReviewReceived($data): void
    {
        Notification::create([
            'user_id' => $data['seller_id'],
            'type' => 'review_received',
            'title' => '⭐ Nova avaliação recebida!',
            'message' => "Seu livro '{$data['book_name']}' recebeu uma avaliação de {$data['rating']} estrelas!",
            'data' => [
                'review_id' => $data['review_id'],
                'book_id' => $data['book_id'],
                'book_name' => $data['book_name'],
                'rating' => $data['rating'],
            ],
        ]);
    }

    /**
     * Notificar quando pedido é entregue (solicitar avaliação)
     */
    private function handleOrderDelivered($data): void
    {
        Notification::create([
            'user_id' => $data['user_id'],
            'type' => 'request_review',
            'title' => '📝 Avalie seu pedido!',
            'message' => "Seu pedido #{$data['order_number']} foi entregue! Que tal avaliar os produtos que você recebeu?",
            'data' => [
                'order_id' => $data['order_id'],
                'order_number' => $data['order_number'],
            ],
        ]);
    }
}
